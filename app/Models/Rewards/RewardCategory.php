<?php

namespace App\Models\Rewards;

use App\Extendables\BaseModel as Model;

class RewardCategory extends Model
{
    protected $guarded = [];

    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
    */
    public function reward()
    {
        return $this->hasMany(Reward::class);
    }
}
