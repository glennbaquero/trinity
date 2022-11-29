<?php

use Illuminate\Database\Seeder;

use App\Models\Rewards\Reward;

class CMEUatToLiveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 0;
        
        if(($handle = fopen('database/csv/rewards.csv', "r")) !== FALSE){
        	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        		if($row != 0) {
        			$this->command->info('writing row ' . $row . ' ' . $data[1]);
    			
    				Reward::create([
    					'reward_category_id' => $data[0],
    					'name' => $data[1],
    					'description' => $data[2],
    					'image_path' => $data[3],
    					'points' => $data[4],
    					'expiry_date' => $data[5] == '' ? null : $data[5],
    				]);
        		}

                $row++;

            }
            fclose($handle);
        }
    }
}
