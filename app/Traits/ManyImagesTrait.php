<?php

namespace App\Traits;

use Storage;
use App\Helpers\FileHelpers;

trait ManyImagesTrait {

    abstract public function images();

    public function addImages($images, $directory = 'images', $file_column = 'image_path') {
        $action = 0;

        if ($images) {            
            foreach ($images as $image) {
                $path = FileHelpers::store($image, $directory);

                $vars = [
                    $file_column => $path,
                ];

                /* Create the image data */
                $this->images()->create($vars);
               
            }

            $action = 1;
        }

        return $action;
    }

    public function removeImage($request, $column = 'image_path', $additionalFilters = []) {
        $filters = [
            'id' => $request->input('id'),
        ];

        $filters = array_merge($filters, $additionalFilters);

        if ($image = $this->images()->where($filters)->first()) {
            Storage::delete('public/' . $image[$column]);
            $image->delete();
        } else {
            abort(403, 'You are not authorized to delete the selected image.');
        }

        return true;
    }

    abstract public function renderRemoveImageUrl();
}