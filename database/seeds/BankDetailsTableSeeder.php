<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\BankDetails\BankDetail;

class BankDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
	        ['branch' => 'EastWest','account_number' => '000-000-001','name' => 'Trinity Health Corp.',],
	        ['branch' => 'BPI','account_number' => '000-001-002','name' => 'Trinity Health Corp.',],
	        ['branch' => 'BDO','account_number' => '001-002-003','name' => 'Trinity Health Corp.',],
	        ['branch' => 'MetroBank','account_number' => '002-003-004','name' => 'Trinity Health Corp.',],
	        ['branch' => 'PNB','account_number' => '003-004-005','name' => 'Trinity Health Corp.',],
	    ];

    	foreach ($banks as $bank) {
            $this->command->info('Adding bank details ' . $bank['name'] . '...');
            BankDetail::create($bank);
    	}
    }
}
