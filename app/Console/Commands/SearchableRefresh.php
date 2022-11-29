<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SearchableRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnt:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Refresh all object's searchable array value";

    protected $models = [
        'App\Models\ActivityLogs\ActivityLog',
        'App\Models\Samples\SampleItem',
        'App\Models\Users\Admin',
        'App\Models\Users\User',
        'App\Models\Pages\Page',
        'App\Models\Pages\PageItem',
        'App\Models\Roles\Role',
        'App\Models\Notifications\Notification',
        'App\Models\Users\User',
        'App\Models\Users\Doctor',
        'App\Models\Specializations\Specialization',
        'App\Models\Prescriptions\Prescription',
        'App\Models\Products\Product',
        'App\Models\Inventories\Inventory',
        'App\Models\Invoices\Invoice',
        'App\Models\Calls\Call',
        'App\Models\ShippingMethod\Standard',
        'App\Models\ShippingMethod\Express',
        'App\Models\BankDetails\BankDetail',
        'App\Models\Provinces\Province',
        'App\Models\Cities\City',
        'App\Models\Articles\Article',
        'App\Models\Rewards\Redeem',
        'App\Models\Points\Point',
        'App\Models\Rewards\Sponsorship',
        'App\Models\Referrals\RequestClaimReferral'
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(PHP_EOL . "Refreshing searchable array values" . PHP_EOL);

        /* Loop through each php files */
        foreach ($this->models as $key => $model) {

            $this->info('Refreshing ' . $model);

            $model::get()->searchable();
            
        }

        $this->info(PHP_EOL . "Searchable array values successfully refreshed!" . PHP_EOL);        
    }
}
