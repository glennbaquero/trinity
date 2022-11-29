<?php

namespace App\Models\Pages;

use App\Extendables\BaseModel as Model;

use Illuminate\Validation\ValidationException;
use Storage;

use App\Helpers\StringHelpers;
use App\Traits\FileTrait;

use App\Models\Pages\Page;

class PageItem extends Model
{
    use FileTrait;
    
    protected static $logAttributes = ['name', 'slug', 'content', 'type'];

    const TYPE_TEXT = 'TEXT';
    const TYPE_HTML = 'HTML';
    const TYPE_IMAGE = 'IMAGE';

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'page' => $this->renderPageColumn('name'),
        ];
    }

    /**
     * @Relationships
     */
    public function page() {
        return $this->belongsTo(Page::class, 'page_id')->withTrashed();
    }

    /**
     * @Getters
     */
    public static function getTypes() {
    	return [
    		['value' => static::TYPE_TEXT, 'label' => 'Text\Link\Label', 'description' => '', 'class' => 'bg-primary'],
    		['value' => static::TYPE_HTML, 'label' => 'HTML\Content', 'description' => '', 'class' => 'bg-info'],
    		['value' => static::TYPE_IMAGE, 'label' => 'Image', 'description' => '', 'class' => 'bg-secondary'],
    	];
    }

    public static function formatToArray($items) {
        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'id' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'slug' => $item->slug,
                'content' => $item->renderContent(),
            ];
        }

        return $result;
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['page_id', 'slug', 'type', 'content', 'name'])
    {
        $vars = $request->only($columns);
        
        if ($request->filled('slug')) {
            $vars['slug'] = StringHelpers::slugify($request->input('slug'));
        }

        if (isset($vars['page_id'])) {

            $parent = Page::withTrashed()->find($vars['page_id']);

            $result = $parent->page_items()->where('slug', $vars['slug']);

            if ($item) {
                $result->where('id', '!=', $item->id);
            }

            $result = $result->first();

            if ($result) {
                throw ValidationException::withMessages([
                    'slug' => 'Slug must be unique within the page.',
                ]);
            }
        }

        switch ($request->input('type')) {
            case static::TYPE_IMAGE:
                    $vars['content'] = static::storeImage($request, $item, 'content', 'page-item-images');
                break;
        }

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }

    public function archiveRestore() {
        $message = 'You have successfully archive area #' . $this->id;
        $action = 1;

        if ($this->trashed()) {
            $message = 'You have successfully restored area #' . $this->id;
            $action = 0;
            $this->restore();
        } else {
            $this->delete();
        }

        return [
            'message' => $message,
            'action' => $action,
        ];
    }

    /**
     * @Render
     */
    public function renderName() {
        return $this->slug;
    }

    public function renderPageColumn($column) {
        $result = null;

        if ($this->page && $this->page[$column]) {
            $result = $this->page[$column];
        }

        return $result;
    }

    public function renderContent() {
        $result = null;

        switch ($this->type) {
            case static::TYPE_IMAGE:
                    $result = Storage::url($this->content);
                break;
            case static::TYPE_HTML:
            case static::TYPE_TEXT:
                    $result = $this->content;
                break;
        }

        return $result;
    }

    public function renderType($column = 'label') {
        return static::renderConstants(static::getTypes(), $this->type, $column);
    }

    public function renderShowUrl() {
        return route('admin.page-items.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('admin.page-items.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('admin.page-items.restore', $this->id);
    }
}
