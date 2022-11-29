<?php

namespace App\Traits;

use Storage;
use App\Helpers\FileHelpers;

trait FileTrait {

	public static function storeImage($request, $item = null, $column = 'image_path', $directory = 'images') {
		if($request->hasFile($column)) {
            if($item && $item[$column] && Storage::exists('public/' . $item[$column])) {
                Storage::delete('public/' . $item[$column]);
            }

            return FileHelpers::store($request->file($column), $directory);
        }

        if ($item) {
            return $item[$column];
        }
	}

    public function renderImagePath($column = 'image_path') {
        $path = $this->getDefaultImage();

        if ($this[$column]) {
            $path = $this->getImageUrl($this[$column]);
        }

        return $path;
    }

    public function renderFilePath($column = 'file_path') {
        $path = null;

        if ($this[$column]) {
            $path = Storage::url($this[$column]);
        }

        return $path;
    }

    protected function getDefaultImage() {
        return '';
    }

    protected function getImageUrl($path) {
        $url = url('/');

        switch (config('web.filesystem')) {
            case 's3':
                    $url = null;
                break;
        }

        return $url . Storage::url($path);
    }

}

