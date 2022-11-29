<?php

namespace App\Models\CreditInvoices;

use App\Extendables\BaseModel as Model;

use App\Models\Users\User;
use App\Models\CreditPackages\CreditPackage;

use Carbon\Carbon;

class CreditInvoice extends Model
{
    /*
	|--------------------------------------------------------------------------
	| @Consts
	|--------------------------------------------------------------------------
	*/

	const PAYNAMICS = 1;


	const PENDING = 1;
	const PAID = 2;
	const CANCELED = 3; 

	/*
	|--------------------------------------------------------------------------
	| @Attributes
	|--------------------------------------------------------------------------
	*/



	/*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function package()
	{
		return $this->belongsTo(CreditPackage::class, 'credit_package_id');
	}

	/*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/


	/**
	 * Store invoice
	 * 
	 * @param  array $request
	 */
	public static function store($request)
	{
		$user = request()->user();
		$package = CreditPackage::findOrfail($request['credit_package_id']);

		$item = $user->credit_invoices()->create([
			'credit_package_id' => $package->id,
			'payment_type' => self::PAYNAMICS,
			'invoice_number' => self::generateInvoiceNumber(),
			'reference_code' => self::generateRefCode(),
			'total' => $package->price,
			'status' => self::PENDING,
			'data' => $package
		]);

		return $item;
	}

	/**
	 * Generate reference code
	 * 
	 * @return String
	 */
    public static function generateRefCode()
    {
        return substr(md5(5 . 'I8evfdEvkwdinD' . time()), 0, 20);
    }


    /**
     * Generate invoice number
     * 
     * @return String
     */
    public static function generateInvoiceNumber()
    {
        $year = Carbon::parse('now')->year;
        $invoiceNumber = 0001;
        $uniqueinvoiceNumber = null;

        if ($latestInvoiceCount = self::count()) {
            $invoiceNumber = $latestInvoiceCount + 1;
        }

        /* Create unique invoiceNumber */
        $uniqueinvoiceNumber = 'CI' . "-" . $year . "-" . sprintf('%04d', $invoiceNumber);

        return $uniqueinvoiceNumber;
    }


	/*
	|--------------------------------------------------------------------------
	| @Renders
	|--------------------------------------------------------------------------
	*/


    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.credit-invoices.archive', $this->id);
    }


    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.credit-invoices.restore', $this->id);
    }


	/**
	 * Render user full name
	 * 
	 */
	public function renderUserName()
	{
		if($this->user) {
			return $this->user->renderFullName();
		}
	}

	/**
	 * Render package name
	 * 
	 */
	public function renderPackageName()
	{
		if($this->data) {
			return json_decode($this->data)->name;
		}
	}

	/**
	 * Render invoice status
	 * 
	 */
	public function renderStatus()
	{

		switch ($this->status) {
			case self::PENDING:
			return 'PENDING';
				break;

			case self::PAID:
			return 'PAID';
				break;

			case self::CANCELED:
			return 'CANCELED';
				break;						
		}

	}

	/**
	 * Render payment type
	 * 
	 */
	public function renderPaymentType()
	{

		switch ($this->payment_type) {
			case self::PAYNAMICS:
			return 'PAYNAMICS';
				break;
								
		}

	}

	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/


	/**
	 * Check if resource can be remove from storage
	 * 
	 * @return  boolean
	 */
	public function canRemove()
	{
		if($this->status == self::PAID) {
			return true;
		}
	}
}
