<?php

namespace App\Models\Rewards;

use App\Extendables\BaseModel as Model;

use App\Helpers\StringHelpers;
use App\Traits\FileTrait;

class Reward extends Model
{
    use FileTrait;

    protected $guarded = [];

    // protected $appends = ['full_image', 'sponsorship_id'];
    protected $appends = ['full_image'];
    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
    */
    public function category()
    {
        return $this->belongsTo(RewardCategory::class, 'reward_category_id')->withTrashed();
    }

    public function redeems()
    {
        return $this->hasMany(Redeem::class);
    }

    public function sponsorships()
    {
        return $this->belongsToMany(Sponsorship::class, 'sponsorship_reward', 'sponsorship_id', 'reward_id');
    }

    /**
     * Store/Update specified resource from storage
     * 
     * @param  Array $request
     * @param  object $item
     */
    public static function store($request, $item = null)
    {
        $admin = \Auth::guard('admin')->user();

        $vars = $request->except(['image_path', 'sponsorships']);
        $vars['reward_category_id'] = 1;
        $vars['image_path'] = static::storeImage($request, $item, 'image_path', 'rewards');        

        if(!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;

    }

    /**
     * Append image full path
     * 
     * @return string $image
     */
    public function getFullImageAttribute()
    {
        return url($this->renderImagePath());
    }


    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderShowUrl($prefix = 'admin')
    {
        return route(StringHelpers::addRoutePrefix($prefix) . 'rewards.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.rewards.archive', $this->id);
    }


    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.rewards.restore', $this->id);
    }

}
