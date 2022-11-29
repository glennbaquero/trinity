<?php

namespace App\Helpers;

use Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Http\File;

class FileHelpers {

	public static function store($image, $directory) {
    	$extension = $image->getClientOriginalExtension();
		$optimized_image = $image;

		if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'bmp' || $extension == 'gif' || $extension == 'png' || $extension == 'svg') {

	    	$optimized_image = Image::make($image)->encode($extension);

	    	$width = $optimized_image->getWidth();
		    $height = $optimized_image->getHeight();

		    if ($width >= $height) {
		    	$optimized_image->resize(700, null, function ($constraint) {
				    $constraint->aspectRatio();
				    $constraint->upsize();
				});
		    } else {
		    	$optimized_image->resize(null, 700, function ($constraint) {
				    $constraint->aspectRatio();
				    $constraint->upsize();
				});
		    }

		    $optimized_image->save();

		    switch (config('web.filesystem')) {
		    	case 's3':
		    			$root = null;
		    		break;
		    	
		    	default:
		    			$root = 'public/';
		    		break;
		    }

		    // $file_path = $root . $directory . '/' . uniqid() . Str::random(40) . '.' . $extension;
		    $file_path = $root . $directory . '/' . uniqid() . Str::random(40) . '.jpg';

	    	Storage::put($file_path, $optimized_image);
		} else {
		    switch (config('web.filesystem')) {
		    	case 's3':
		    			$root = null;
		    		break;
		    	
		    	default:
		    			$root = 'public/';
		    		break;
		    }
		    $file_path = $root . $directory . '/' . uniqid() . Str::random(40) . '.' . $extension;

	    	Storage::disk('local')->put($file_path, file_get_contents($optimized_image));
		}

    	return $file_path;
	}
}