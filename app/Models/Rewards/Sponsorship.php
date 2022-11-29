<?php

namespace App\Models\Rewards;

use App\Extendables\BaseModel as Model;
use Laravel\Scout\Searchable;

class Sponsorship extends Model
{
    use Searchable;

    protected $guarded = [];

    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
    */
    public function rewards()
    {
        return $this->belongsToMany(Reward::class, 'sponsorship_reward', 'reward_id', 'sponsorship_id');
    }

    /**
     * Store/Update specified resource from storage
     * 
     * @param  Array $request
     * @param  object $item
     */
    public static function store($request, $item = null)
    {

        $vars = $request->except([]);

        if(!$item) {
            $item = static::create($vars); 
        } else {
            $item->update($vars);
        }

        return $item;        

    }

    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderShowUrl()
    {
        return route('admin.sponsorships.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.sponsorships.archive', $this->id);
    }


    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.sponsorships.restore', $this->id);
    }

    /**
     * Render description of specified resource from storage
     * 
     * @return String
     */
    public function renderShortDescription()
    {
        if($this->description) {
            return str_limit(strip_tags($this->description), 100);
        }        
    }
}
