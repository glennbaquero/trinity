<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

trait ArchiveableTrait {

	use SoftDeletes;

    public function archive() {
        if (!$this->trashed() && $this->isArchiveable()) {
            $this->delete();
        } else {
            throw ValidationException::withMessages([
                'deleted_at' => $this->archiveErrorMessage(),
            ]);
        }

        return true;
    }

    public function unarchive() {
        if ($this->trashed() && $this->isRestorable()) {
            $this->restore();
        } else {
            throw ValidationException::withMessages([
                'deleted_at' => $this->restoreErrorMessage(),
            ]);
        }

        return true;
    }

    public function isArchiveable() {
        return true;
    }

    public function isRestorable() {
        return true;
    }

    public function archiveErrorMessage() {
        $result = 'Item';

        if ($this->isArchiveable()) {
            $result .= ' has already been archived.';
        } else {
            $result .= ' cannot be archived.';
        }

        return $result;
    }

    public function restoreErrorMessage() {
        $result = 'Item';

        if ($this->isRestorable()) {
            $result .= ' has already been restored.';
        } else {
            $result .= ' cannot be restored.';
        }

        return $result;
    }

    public function renderArchiveUrl() {}
    public function renderRestoreUrl() {}
}