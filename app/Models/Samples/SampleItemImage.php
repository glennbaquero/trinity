<?php

namespace App\Models\Samples;

use Illuminate\Database\Eloquent\Model;

use App\Traits\FileTrait;

class SampleItemImage extends Model
{
    use FileTrait;

    protected $guarded = [];

    public function sample_item() {
    	return $this->belongsTo(SampleItemImage::class, 'sample_item_id');
    }

    public static function formatToArray($items) {
    	$result = [];

    	foreach ($items as $item) {
    		$result[] = [
    			'id' => $item->id,
    			'path' => $item->renderImagePath(),
    		];
    	}

    	return $result;
    }
}
