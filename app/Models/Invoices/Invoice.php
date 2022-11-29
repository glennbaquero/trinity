<?php

namespace App\Models\Invoices;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ArchiveableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Scout\Searchable;
use App\Traits\HelperTrait;
use Carbon\Carbon;

use App\Traits\FileTrait;
use App\Helpers\CustomLogHelper;

use App\Models\Invoices\InvoiceItem;
use App\Models\Invoices\InvoiceFailedTransaction;
use App\Models\Users\User;
use App\Models\Carts\Cart;
use App\Models\StatusTypes\StatusType;
use App\Models\Referrals\SuccessReferral;
use App\Models\Vouchers\UsedVoucher;
use App\Models\Vouchers\UserVoucher;


class Invoice extends Model
{

    use ArchiveableTrait;
    use Searchable;
    use HelperTrait;
    use LogsActivity;
    use FileTrait;

	/**
	 * Attributes that are mass assignable
	 * 
	 * @var array
	 */
	protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | @Const
    |--------------------------------------------------------------------------
    */
    const PAID = 1;
    const UNPAID = 0;

    const PAYNAMICS = 2;
    const BANKDEPOSIT = 4;
    const COD = 5;
    const GCASH = 6;
    const EWALLET = 7;

	/*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function invoiceItems()
	{
		return $this->hasMany(InvoiceItem::class);
	}
    
	public function user()
	{
		return $this->belongsTo(User::class)->withTrashed();
	}

	public function cart()
	{
		return $this->belongsTo(Cart::class)->withTrashed();
	}

    public function status()
    {
        return $this->belongsTo(StatusType::class, 'status_id')->withTrashed();
    }

    public function failed_transaction()
    {
        return $this->hasOne(InvoiceFailedTransaction::class);
    }

    public function successReferral() 
    {
        return $this->hasOne(SuccessReferral::class);
    }

    public function usedVoucher()
    {
        return $this->hasOne(UsedVoucher::class);
    }

    public function userVoucher()
    {
        return $this->belongsTo(UserVoucher::class, 'voucher');
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
            'invoice_number' => $this->invoice_number,
            'user_first_name' => $this->user->first_name,
            'user_last_name' => $this->user->last_name,
        ];
    }

    /**
     * Get invoices 
     * 
     * @param   array $months
     * @param   string $year
     */
    public static function getInvoices($months, $year, $today = false) 
    {

        $invoices = Invoice::whereIn(\DB::raw('month(created_at)'), $months)
                        ->where(\DB::raw('year(created_at)'), $year);
        if($today) {
            $now = Carbon::now();
            $invoices = $invoices->whereIn('created_at', [ $now->startOfDay(), $now->endOfDay() ]);
        }

        return $invoices->where('payment_status', Invoice::PAID)->get();

    }

    public static function generateRefCode()
    {
        return substr(md5(5 . 'I8evfdEvkwdinD' . time()), 0, 20);
    }

    public static function generateOrderNumber()
    {
        $year = Carbon::parse('now')->year;
        $orderNumber = 0001;
        $uniqueorderNumber = null;

        if ($latestInvoiceCount = Invoice::count()) {
            $orderNumber = $latestInvoiceCount + 1;
        }

        /* Create unique orderNumber */
        $uniqueorderNumber = 'BC' . "-" . $year . "-" . sprintf('%04d', $orderNumber);

        return $uniqueorderNumber;
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
            $logMessage = 'Invoice has been created';
        }

        return $logMessage;
    }


    public function returningStocks()
    {
        foreach ($this->invoiceItems as $key => $item) {
            $item->product->inventory->returnStocks($item->quantity);
        }
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
    * Give points to both patient and doctor after a transaction
    *
    * @param object $invoice
    */
    public function distributePoints()
    {
        foreach ($this->invoiceItems as $invoiceItem) {

            $this->user->points()->create([
                'points' => $invoiceItem->product->client_points * $invoiceItem->quantity
            ]);
            
            if($invoiceItem->product->is_free_product !== 1) {
                $user = $this->user;
                $doctor = $invoiceItem->doctor ? $invoiceItem->doctor : null; 
                if($doctor) {
                    $doctor->points()->create([
                        'points' => $invoiceItem->product->doctor_points * $invoiceItem->quantity
                    ]);                    

                    if($user->firstTimeBuyer($doctor)) {
                        $user->purchased()->attach($doctor->id);                                                
                        $secretaries = $doctor->secretaries;
                        foreach ($secretaries as $key => $secretary) {
                            if($this->user_id != $secretary->id) {
                                $secretary->points()->create(['points' => $invoiceItem->product->secretary_points * $invoiceItem->quantity]);
                            }
                        }             
                    }
                }
            }
        }
    }

	/*
	|--------------------------------------------------------------------------
	| @Renders
	|--------------------------------------------------------------------------
	*/	

    /**
     * Render total sales
     * 
     * @param  date $dateRange
     * @param  boolean $hasFormat 
     */
    public static function renderTotalSales($dateRange = null, $hasFormat = false)
    {
        $sales = Invoice::where('payment_status', Invoice::PAID);
        
        if($dateRange) {
            $sales->whereBetween('created_at', [$dateRange['start_date'], $dateRange['end_date']]);
        }

        $sales = $sales->sum('grand_total');

        if($hasFormat) {
            $sales = 'Php ' .number_format($sales, 2, '.', ',');
        }

        return $sales;
    }


    /**
     * Render full address of specified invoice from storage
     * 
     * @return String
     */
    public function renderFullAddress()
    {
        $address = "{$this->shipping_street}, {$this->shipping_unit}, {$this->shipping_region}, {$this->shipping_province}, {$this->shipping_city}, {$this->shipping_zip}";

        return $address; 
     }

    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderShowUrl()
    {
        return route('admin.invoices.show', [$this->invoice_number, $this->id]);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.invoices.archive', $this->id);
    }


    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.invoices.restore', $this->id);
    }

    /**
     * Render print url of specific resource in storage
     * 
     * @return string
     */
    public function renderPrintUrl()
    {
        return route('admin.invoices.print-invoice', [$this->invoice_number, $this->id]);
    }

    /**
     * Render failed transaction url of specific resource in storage
     * 
     * @return string
     */
    public function renderFailedTransactionFormUrl($type)
    {
        return route('admin.invoices.failed-transaction', [$this->invoice_number, $this->id, $type]);
    }

    /**
     * Render total items bought of specified resource from storage
     * 
     * @return int
     */
    public function renderTotalItemsBought()
    {
    	return $this->invoiceItems->sum('quantity');
    }

    /**
     * Render payment status {label} of speicified resource from storage
     * 
     * @return String
     */
    public function renderPaymentStatus()
    {
        return [
            'status' => $this->payment_status,
            'text' => $this->payment_status ? 'Paid' : 'Unpaid'
        ];
    }

    /**
     * Render specified order status from storage
     * 
     * @return String
     */
    public function renderOrderStatus()
    {
        if($this->status) {
            return $this->status;
        }
    }

    /**
     * Render grand total
     * 
     * @param  boolean $hasFormat
     */
    public function renderGrandTotal($hasFormat = false)
    {
        if($hasFormat) {
            return 'Php '. number_format($this->grand_total, 2, '.', ',');
        }

        return $this->grand_total;
    }

    public function renderSubTotal($hasFormat = false)
    {
        if($hasFormat) {
            return 'Php '. number_format($this->sub_total, 2, '.', ',');
        }
        return $this->sub_total;

    }

    /**
     * Render shipping fee of specified invoice
     * 
     * @param  boolean $hasFormat
     */
    public function renderShippingFee($hasFormat = false) 
    {
        if($hasFormat) {
            return 'Php '. number_format($this->shipping_fee, 2, '.', ',');
        }
        return $this->shipping_fee;
    } 


    public function renderInvoiceSubtotal()
    {
        $total = $this->grand_total - ($this->shipping_fee + $this->discount);
        return 'Php '. number_format($total, 2, '.', ',');;
    }

	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/	

    /**
     * Check if status is complete for specified resource
     * 
     * @return boolean
     */
    public function checkIfCompleted($id)
    {
        $status = StatusType::withTrashed()->find($id);

        return $status->action_type === StatusType::COMPLETED;
    }

    /**
     * Check if process is end for specified resource
     * 
     * @return boolean
     */
    public function checkIfProcssIsEnd()
    {

        if($this->status) {
            if(in_array($this->status->action_type, [StatusType::COMPLETED, StatusType::CANCELLED])) {
                return true;
            }
        }
        return false;

    }

    /*
    |--------------------------------------------------------------------------
    | @Getters
    |--------------------------------------------------------------------------
    */
    public function getProducts($cartItem)
    {
        $item = collect($cartItem->product)->only(['brand_name', 'price', 'image_path']);
        $item['quantity'] = $cartItem->quantity;

        return $item->all();
    }

    public function getUserInvoice()
    {
        $invoice = collect($this)->except([
            'user_id', 'cart_id', 'status_id',
            'payment_status', 'updated_at', 'deleted_at',
            'deposit_slip_path'
        ]);
        $invoiceItems = [];
        foreach ($this->invoiceItems->all() as $item) {
            array_push($invoiceItems, [
                'created_at' => $item->renderCreatedAt(),
                'data' => $item->data,
                'id' => $item->id,
                'invoice_id' => $item->invoice_id,
                'price' => $item->price,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'total_price' => $item->total_price,
                'updated_at' => $item->updated_at,
                'invoice_number' => $this->invoice_number,
                'stocks' => $item->product ? $item->product->renderStocks() : 0
            ]);
        }

        $invoice['deposit_slip_path'] = $this->deposit_slip_path ? $this->renderImagePath('deposit_slip_path') : null;
        $invoice['discount_card_path'] = $this->discount_card_path ? $this->renderImagePath('discount_card_path') : null;        
        $invoice['products'] = $invoiceItems;
        $invoice['image_path'] = $this->invoiceItems->first()->product->full_image;
        $invoice['deliver_to'] = $this->user->only(['first_name', 'last_name', 'email', 'mobile_number']);
        $invoice['completed'] = $this->checkIfCompleted($this->status_id);
        $invoice['status'] = $this->status->name;
        $invoice['cancelled'] = $this->status->action_type === 3;
        $invoice['reason'] = $this->failed_transaction ? $this->failed_transaction->reason : null; 

        return $invoice->all();
    }

    public static function getPaymentType() {
        return [
            ['value' => static::BANKDEPOSIT, 'label' => 'BANK DEPOSIT', 'class' => '#0275d8'],
            ['value' => static::PAYNAMICS, 'label' => 'PAYNAMICS', 'class' => '#5cb85c'],
            ['value' => static::COD, 'label' => 'COD', 'class' => '#5bc0de'],
            ['value' => static::GCASH, 'label' => 'GCASH', 'class' => '#177cfe'],
            ['value' => static::EWALLET, 'label' => 'EWALLET', 'class' => '#eb7a34'],
        ];
    }

    public function renderPaymentType($name) {
        return $this->renderConstants(static::getPaymentType(), $this->payment_method, $name);
    }

}
