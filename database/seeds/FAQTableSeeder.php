<?php

use Illuminate\Database\Seeder;

use App\Models\Faqs\Faq;

class FAQTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 0;
        
        if(($handle = fopen('database/csv/faqs.csv', "r")) !== FALSE){
        	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        		if($row != 0) {
        			$this->command->info('writing row ' . $row . ' ' . $data[1]);

        			// $item = new Product();
        			Faq::create([
        				'app' => $data[0],
        				'question' => $data[1],
        				'answer' => $data[2],
        			]);

        			// $item->save();
        		}

                $row++;

            }
            fclose($handle);
        }
    }
}
