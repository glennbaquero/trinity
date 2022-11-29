<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use App\Models\Invoices\Invoice;
use App\Models\Invoices\InvoiceItem;
use App\Models\Products\Product;
use App\Models\Users\User;
use App\Models\StatusTypes\StatusType;

class InvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Invoice::truncate();
    	InvoiceItem::truncate();

		$this->populateSeeder();
    }


    public function populateSeeder()
    {
    	foreach ($this->generateData() as $key => $data) {
 		
 			$invoice = Invoice::create($data);
 			$this->createInvoiceItems($invoice);

    	}

    }

	public function generateData() 
	{

		$faker = Faker::create();

		$invoices = [
			[
				'user_id' => User::inRandomOrder()->first()->id,
				'cart_id' => 1,
				'status_id' => StatusType::inRandomOrder()->first()->id,
				'invoice_number' => 'TRNTY-000001',
				'code' => substr(md5(5 . 'I8evfdEvkwdinD' . time()), 0, 20),
				'shipping_name' => 'Cardo Dalisay',
				'shipping_email' => 'cardo.dalisay@email.com',
				'shipping_mobile' => '09123456789',
				'payment_method' => 2,
				'payment_status' => rand(0, 1),
				'shipping_method' => 1,
				'shipping_name' => 'Sample User',
				'shipping_email' => 'sample@email.com',
				'shipping_mobile' => '09123456789',
				'shipping_unit' => $faker->buildingNumber,
				'shipping_street' => $faker->streetName,
				'shipping_region' => $faker->state,
				'shipping_province' => $faker->state,
				'shipping_city' => $faker->city,
				'shipping_zip' => $faker->postcode,
				'shipping_landmark' => $faker->cityPrefix,
				'shipping_fee' => 0,
				'discount' => 0,
				'sub_total' => 0,
				'grand_total' => 400,
				'created_at' => '2019-08-15 15:31:44'
			],

			[
				'user_id' => User::inRandomOrder()->first()->id,
				'cart_id' => 2,
				'status_id' => StatusType::inRandomOrder()->first()->id,				
				'invoice_number' => 'TRNTY-000002',
				'code' => substr(md5(5 . 'I8evfdEvkwdinD' . time()), 0, 20),
				'payment_method' => 2,
				'shipping_name' => 'Cardo Dalisay',
				'shipping_email' => 'cardo.dalisay@email.com',
				'shipping_mobile' => '09123456789',
				'payment_status' => rand(0, 1),				
				'shipping_method' => 1,
				'shipping_name' => 'Sample User',
				'shipping_email' => 'sample@email.com',
				'shipping_mobile' => '09123456789',
				'shipping_unit' => $faker->buildingNumber,
				'shipping_street' => $faker->streetName,
				'shipping_region' => $faker->state,
				'shipping_province' => $faker->state,
				'shipping_city' => $faker->city,
				'shipping_zip' => $faker->postcode,
				'shipping_landmark' => $faker->cityPrefix,
				'shipping_fee' => 0,
				'discount' => 0,
				'sub_total' => 0,
				'grand_total' => 0,
				'grand_total' => 740,
				'created_at' => '2019-08-28 15:31:44'
			],

			[
				'user_id' => User::inRandomOrder()->first()->id,
				'cart_id' => 3,
				'status_id' => StatusType::inRandomOrder()->first()->id,				
				'invoice_number' => 'TRNTY-000003',
				'code' => substr(md5(5 . 'I8evfdEvkwdinD' . time()), 0, 20),
				'payment_method' => 5,
				'shipping_name' => 'Cardo Dalisay',
				'shipping_email' => 'cardo.dalisay@email.com',
				'shipping_mobile' => '09123456789',
				'payment_status' => rand(0, 1),				
				'shipping_method' => 1,
				'shipping_name' => 'Sample User',
				'shipping_email' => 'sample@email.com',
				'shipping_mobile' => '09123456789',
				'shipping_unit' => $faker->buildingNumber,
				'shipping_street' => $faker->streetName,
				'shipping_region' => $faker->state,
				'shipping_province' => $faker->state,
				'shipping_city' => $faker->city,
				'shipping_zip' => $faker->postcode,
				'shipping_landmark' => $faker->cityPrefix,
				'shipping_fee' => 0,
				'discount' => 0,
				'sub_total' => 0,
				'grand_total' => 1153,
				'created_at' => '2019-09-04 15:31:44'
			],

			[
				'user_id' => User::inRandomOrder()->first()->id,
				'cart_id' => 4,
				'status_id' => StatusType::inRandomOrder()->first()->id,				
				'invoice_number' => 'TRNTY-000004',
				'code' => substr(md5(5 . 'I8evfdEvkwdinD' . time()), 0, 20),
				'payment_method' => 4,
				'shipping_name' => 'Cardo Dalisay',
				'shipping_email' => 'cardo.dalisay@email.com',
				'shipping_mobile' => '09123456789',
				'payment_status' => rand(0, 1),				
				'shipping_method' => 1,
				'shipping_name' => 'Sample User',
				'shipping_email' => 'sample@email.com',
				'shipping_mobile' => '09123456789',
				'shipping_unit' => $faker->buildingNumber,
				'shipping_street' => $faker->streetName,
				'shipping_region' => $faker->state,
				'shipping_province' => $faker->state,
				'shipping_city' => $faker->city,
				'shipping_zip' => $faker->postcode,
				'shipping_landmark' => $faker->cityPrefix,
				'shipping_fee' => 0,
				'discount' => 0,
				'sub_total' => 0,
				'grand_total' => 300,
				'created_at' => '2019-09-10 15:31:44'
			],

			[
				'user_id' => User::inRandomOrder()->first()->id,
				'cart_id' => 5,
				'status_id' => StatusType::inRandomOrder()->first()->id,				
				'invoice_number' => 'TRNTY-000005',
				'code' => substr(md5(5 . 'I8evfdEvkwdinD' . time()), 0, 20),
				'payment_method' => 5,
				'shipping_name' => 'Cardo Dalisay',
				'shipping_email' => 'cardo.dalisay@email.com',
				'shipping_mobile' => '09123456789',
				'payment_status' => rand(0, 1),				
				'shipping_method' => 1,
				'shipping_name' => 'Sample User',
				'shipping_email' => 'sample@email.com',
				'shipping_mobile' => '09123456789',
				'shipping_unit' => $faker->buildingNumber,
				'shipping_street' => $faker->streetName,
				'shipping_region' => $faker->state,
				'shipping_province' => $faker->state,
				'shipping_city' => $faker->city,
				'shipping_zip' => $faker->postcode,
				'shipping_landmark' => $faker->cityPrefix,
				'shipping_fee' => 0,
				'discount' => 0,
				'sub_total' => 0,
				'grand_total' => 1090,
				'created_at' => '2019-10-02 15:31:44'
			],


		];

		return $invoices;

	}

	public function createInvoiceItems($invoice)
	{

		$items = rand(1, 3);

		for ($i=0; $i < $items; $i++) { 

			/** Get random product */
			$product = Product::inRandomOrder()->first();

			$quantity = rand(1, 2);
			$totalPrice = $product->price * $quantity;


			$vars = [
				'invoice_id' => $invoice->id,
				'product_id' => $product->id,
				'data' => $product,
				'quantity' => $quantity,
				'price' => $product->price,
				'total_price' => $totalPrice
			];

			$invoice->invoiceItems()->create($vars);

		}

	}

}