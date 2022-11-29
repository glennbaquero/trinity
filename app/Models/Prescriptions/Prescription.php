<?php

namespace App\Models\Prescriptions;

use App\Models\Users\User;
use App\Models\Products\Product;
use App\Models\Products\ProductParent;

use App\Helpers\StringHelpers;
use App\Traits\FileTrait;
use Laravel\Scout\Searchable;

use App\Extendables\BaseModel as Model;

class Prescription extends Model
{

	use FileTrait;
	use Searchable;

 	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function user()
	{
		return $this->belongsTo(User::class)->withTrashed();
	}

	public function parent()
	{
		return $this->belongsTo(ProductParent::class, 'product_id')->withTrashed();
	}

	/*
	|--------------------------------------------------------------------------
	| Renders
	|--------------------------------------------------------------------------
	*/
	public function renderArchiveUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'prescriptions.archive', $this->id);
	}

	public function renderShowUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'prescriptions.show', $this->id);
	}

	/*
	|--------------------------------------------------------------------------
	| Methods
	|--------------------------------------------------------------------------
	*/
	public function toSearchableArray()
	{
		return [
			'id' => $this->id,
			'user_first_name' => $this->user ? $this->user->first_name : null,
			'user_last_name' => $this->user ? $this->user->last_name : null,
		];
	}

}
