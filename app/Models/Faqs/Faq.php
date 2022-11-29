<?php

namespace App\Models\Faqs;

use App\Extendables\BaseModel as Model;

class Faq extends Model
{

	const APP_CARE = 0;
	const APP_DOC = 1;
	const APP_TEAM = 2;

    public function category() {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id')->withTrashed();
    }

    /**
     * Store/Update specified resource from storage
     * 
     * @param  Array $request
     * @param  object $item
     */
    public static function store($request, $item = null)
    {
        $vars = $request->all();

        if(!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }

    /**
     * Getters
     */
    public static function getAppCompatible() {
        return [
            ['value' => static::APP_CARE, 'label' => 'Care App', 'class' => 'bg-warning'],
            ['value' => static::APP_DOC, 'label' => 'Doc App', 'class' => 'bg-green'],
            // ['value' => static::APP_TEAM, 'label' => 'Team App', 'class' => 'bg-danger'],
        ];
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
        return route('admin.faqs.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.faqs.archive', $this->id);
    }

    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.faqs.restore', $this->id);
    }

    public function renderAppCompatibilityLabel() {
        return $this->renderConstants(static::getAppCompatible(), $this->app, 'label');
    }

    public function renderAppCompatibilityClass() {
        return $this->renderConstants(static::getAppCompatible(), $this->app, 'class');
    }
}
