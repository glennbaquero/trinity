<?php

namespace App\Traits;

use Laravel\Scout\Searchable;

trait SearchableTrait {

	use Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
        ];
    }
}