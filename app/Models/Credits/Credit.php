<?php

namespace App\Models\Credits;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{

	protected $fillable = [
		'parent_id', 'parent_type', 'value'
	];


    /*
	|--------------------------------------------------------------------------
	| @Consts
	|--------------------------------------------------------------------------
	*/


	/*
	|--------------------------------------------------------------------------
	| @Attributes
	|--------------------------------------------------------------------------
	*/



	/*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/


	public function parent()
	{
		return $this->morphTo();	
	}


	/*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/



	/*
	|--------------------------------------------------------------------------
	| @Renders
	|--------------------------------------------------------------------------
	*/



	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/
}