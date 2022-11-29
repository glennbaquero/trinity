<?php

namespace App\Models\Rewards;

use App\Models\Users\Doctor;
use App\Extendables\BaseModel as Model;

use App\Helpers\StringHelpers;
use Laravel\Scout\Searchable;

class Redeem extends Model
{
    use Searchable;

    protected $guarded = [];

    const FOR_UPDATE = 0;
    const APPROVED = 1;
    const REJECTED = 2;

    protected $casts = [
        'data' => 'sponsorships'
    ];

    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
    */
    public function reward()
    {
        return $this->belongsTo(Reward::class)->withTrashed();
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class)->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | Renders
    |--------------------------------------------------------------------------
    */

    public function renderStatus() {
        foreach (self::getStatus() as $key => $status) {
            if($status['id'] == $this->status) {
                return $status;
            }
        }

        return null;
    }

    public function renderApproveUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'redeems.approve', $this->id);
    }

    public function renderRejectUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'redeems.reject', $this->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function getStatus() {
        return [
            ['id' => self::FOR_UPDATE, 'value' => 'For Update'],
            ['id' => self::APPROVED, 'value' => 'Approved'],
            ['id' => self::REJECTED, 'value' => 'Rejected'],
        ];
    }

    public function getSponsorships() {
        $sponsorships = json_decode($this->sponsorships);
        $result = [];

        foreach ($sponsorships as $sponsorship) {
            array_push($result, [
                'name' => $sponsorship->name,
                'renderShowUrl'=> Sponsorship::find($sponsorship->id) ? Sponsorship::find($sponsorship->id)->renderShowUrl() : null,
            ]);
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'doctor_first_name' => $this->doctor->first_name,
            'doctor_last_name' => $this->doctor->last_name,
            'reward' => $this->reward->name
        ];
    }

}
