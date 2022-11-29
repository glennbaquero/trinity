<?php

namespace App\Models\Faqs;

use App\Extendables\BaseModel as Model;
use Laravel\Scout\Searchable;

class FaqCategory extends Model
{

    /** Set table */
    protected $table = 'faq_categories';

    public function faqs() {
        return $this->hasMany(Faq::class);
    }

    /**
     * Store/Update specified resource from storage
     * 
     * @param  Array $request
     * @param  object $item
     */
    public static function store($request, $item = null)
    {
        // $vars = $request->all();
        $vars['name'] = $request->name;

        if(!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }

    /**
     * Renderers
     */
    
    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderShowUrl()
    {
        return route('admin.faq-categories.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.faq-categories.archive', $this->id);
    }

    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.faq-categories.restore', $this->id);
    }
}
