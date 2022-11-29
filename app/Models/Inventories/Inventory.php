<?php

namespace App\Models\Inventories;

use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;
use App\Traits\HelperTrait;

use App\Models\Products\Product;

use App\Helpers\CustomLogHelper;

class Inventory extends Model
{

    use HelperTrait;
    use Searchable;

    const OKAY = 100;
    const WARNING = 50;
    const DANGER = 20;
    const OUT_OF_STOCK = 0;
    /**
     * The attributes that are mass assignable
     * 
     * @var array
     */
	protected $guarded = [];	

    /*
    |--------------------------------------------------------------------------
    | @Relationships
    |--------------------------------------------------------------------------
    */

    public function product()
    {
    	return $this->belongsTo(Product::class)->withTrashed();
    }



    /*
    |--------------------------------------------------------------------------
    | @Methods
    |--------------------------------------------------------------------------
    */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product->name,
            'sku' => $this->product->sku,
            'stocks' => $this->stocks
        ];
    }

    public function deductStocksBy(int $quantity)
    {
        $this->stocks = $this->stocks - $quantity;
        $this->save();
    }
    
    /**
     * Returning stocks when transaction is cancelled
     * 
     * @param  int    $quantity 
     */
    public function returnStocks(int $quantity)
    {
        $this->stocks = $this->stocks + $quantity;
        $this->save();

        $logMessage = "Return {$quantity} stocks";
        $this->createLog(\Auth::guard('admin')->user()->id, $logMessage);
    }

    /**
     * Render description for log
     * 
     * @param  string $eventName 
     */
    public function getDescriptionForEvent(string $eventName): string 
    {
        $logMessage = '';
   
        if($eventName == 'created') {
            $logMessage = 'Created initial stocks: '. $this->stocks;
        }

        return $logMessage;
    }
    

    /**
     * Create log for specified storage
     * using Spatie\Activitylog\Traits\LogsActivity
     * 
     * @param  string $logMessage
     */
    public function createLog($causer, $logMessage)
    {
        CustomLogHelper::createLog($causer, $this, $logMessage);
    }


    /**
     * Find inventory status
     * 
     * @param  int $value
     * @return array
     */
    public function findStatus($value)
    {   
        foreach(self::renderInventoryStatuses() as $status) {
            if($status['value'] == $value) {
                return $status;
            }
        }
    }

    /**
     * Count stocks report by status
     * 
     * @param  int $value
     * @return int
     */
    public static function countStocksReportByStatus($value)
    {

        switch ($value) {

            case Inventory::OKAY:
                return Inventory::where('stocks', '>=', Inventory::OKAY)
                                ->orWhere('stocks', '>', Inventory::WARNING)
                                ->count();
                break;

            case Inventory::WARNING:
                return Inventory::where([['stocks', '<=', Inventory::WARNING], ['stocks', '>', Inventory::DANGER]])->count();
                break;

            case Inventory::DANGER:
                return Inventory::where([['stocks', '<=', Inventory::DANGER], ['stocks','!=', Inventory::OUT_OF_STOCK]])->count();
                break;

            case Inventory::OUT_OF_STOCK:
                return Inventory::where('stocks', 0)->count();
                break;                
        }
    }

    /**
     * Fetching status report
     * 
     * @return collection
     */
    public static function fetchInventoryStatusReport()
    {
        return collect([
            ['label' => 'Okay', 'class' => 'bg-success', 'icon' => 'fas fa-check', 'count' => self::countStocksReportByStatus(Inventory::OKAY)],
            ['label' => 'Warning', 'class' => 'bg-warning', 'icon' => 'fas fa-exclamation-circle', 'count' => self::countStocksReportByStatus(Inventory::WARNING)],
            ['label' => 'Danger', 'class' => 'bg-danger', 'icon' => 'fas fa-exclamation-triangle', 'count' => self::countStocksReportByStatus(Inventory::DANGER)],
            ['label' => 'Out Of Stock', 'class' => 'bg-danger', 'icon' => 'fas fa-times', 'count' => self::countStocksReportByStatus(Inventory::OUT_OF_STOCK)],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | @Renders
    |--------------------------------------------------------------------------
    */
    
    /**
     * Render inventory statuses
     * 
     * @return collection
     */
    public static function renderInventoryStatuses()
    {
        return collect([
            ['label' => 'Okay', 'value' => Inventory::OKAY, 'class' => 'badge badge-success'],
            ['label' => 'Warning', 'value' => Inventory::WARNING, 'class' => 'badge badge-warning'],
            ['label' => 'Danger', 'value' => Inventory::DANGER, 'class' => 'badge badge-danger'],
            ['label' => 'Out of stock', 'value' => Inventory::OUT_OF_STOCK, 'class' => 'badge badge-danger'],            
        ]);
    }
    
    /**
     * Render specific inventory status
     * 
     */
    public function renderStatus()
    {
        switch ($this->stocks) {
            case Inventory::OUT_OF_STOCK:
                return $this->findStatus(Inventory::OUT_OF_STOCK);
                break;
            case $this->stocks >= Inventory::OKAY || $this->stocks > Inventory::WARNING:
                return $this->findStatus(Inventory::OKAY);
                break;
            case $this->stocks <= Inventory::WARNING && $this->stocks > Inventory::DANGER;
                return $this->findStatus(Inventory::WARNING); 
                break;
            case $this->stocks <= Inventory::DANGER && $this->stocks != Inventory::OUT_OF_STOCK;
                return $this->findStatus(Inventory::DANGER);
                break;
        }
    }

    
    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderShowUrl()
    {
        return route('admin.inventories.show', $this->id);
    }    

    /*
    |--------------------------------------------------------------------------
    | @Checkers
    |--------------------------------------------------------------------------
    */



}
