<?php

use Illuminate\Database\Seeder;

use App\Models\CreditPackages\CreditPackage;

class CreditPackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 0;
        if(($handle = fopen('database/csv/credit_packages.csv', "r")) !== FALSE){ // Check if CSV file exists
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { // Parse data inside the file
                if ($row > 0) { // row check

                    $this->command->info('Seeding record ' . $row . ' ' . $data[0]); // Traces seeded rows in terminal

                    // Seed model with csv data
                    $item = new CreditPackage(); 
                    $item->name = $data[0]; 
                    $item->credits = $data[1]; 
                    $item->price = $data[2]; 
                    $item->status = $data[3]; 
                    $item->save();
                }
                $row++;
            }
            fclose($handle);
        }
    }
}
