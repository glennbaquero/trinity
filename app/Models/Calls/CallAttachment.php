<?php

namespace App\Models\Calls;

use App\Models\Calls\Call;
use App\Extendables\BaseModel as Model;

class CallAttachment extends Model
{
	/*
    |--------------------------------------------------------------------------
    | @Const
    |--------------------------------------------------------------------------
    */
    const FILES = 1;
    const SIGNATURE = 2;
    const UPLOADED_PHOTO = 3;
    
    /*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/
	public function call()
	{
		return $this->belongsTo(Call::class)->withTrashed();
	}


    /*
	|--------------------------------------------------------------------------
	| @Render
	|--------------------------------------------------------------------------
	*/

	public function renderShowUrl()
	{
		if($this->file_path) {
			return \Storage::url($this->file_path);
		}
	}


}
