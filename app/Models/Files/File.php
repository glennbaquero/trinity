<?php

namespace App\Models\Files;

use App\Models\Articles\Article;
use App\Extendables\BaseModel as Model;

class File extends Model
{
    
    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function fileable()
	{
		return $this->morphTo();
	}

}
