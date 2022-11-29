<?php

namespace App\Models\Users;

use App\Extendables\BaseUser as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\Admin\Auth\ResetPassword;
use Illuminate\Validation\ValidationException;
use Laravel\Scout\Searchable;
use Password;

use App\Models\Referrals\RequestClaimReferral;

class Admin extends Authenticatable
{

    use HasRoles;
    use Searchable;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
   
    public function distributes()
    {
        return $this->hasMany(RequestClaimReferral::class);
    }

    public function disapproves()
    {
        return $this->hasMany(RequestClaimReferral::class);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    public function sendPasswordResetNotification($token) 
    {
        $this->notify(new ResetPassword($token));
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Checkers
    |--------------------------------------------------------------------------
    */
    public function isRoleEditable(): bool 
    {
        return $this->id !== 1;
    }

    public function isArchiveable(): bool 
    {
        return $this->id !== 1;
    }

    public function isRestorable(): bool 
    {
        return $this->id !== 1;
    }

    public function broker() {
        return Password::broker('admins');
    }


    /*
    |--------------------------------------------------------------------------
    | Renders
    |--------------------------------------------------------------------------
    */
    public function renderFullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function renderRoleNames() 
    {
        $result = 'None';

        if (count($this->getRoleNames())) {
            $result = implode(', ', $this->getRoleNames()->toArray());
        }

        return $result;
    }

    public function renderShowUrl() 
    {
        return route('admin.admin-users.show', $this->id);
    }

    public function renderArchiveUrl() 
    {
        return route('admin.admin-users.archive', $this->id);
    }

    public function renderRestoreUrl() 
    {
        return route('admin.admin-users.restore', $this->id);
    }

}