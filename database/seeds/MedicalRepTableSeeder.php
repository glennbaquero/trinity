<?php

use Illuminate\Database\Seeder;

use App\Models\Users\MedicalRepresentative;

class MedicalRepTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 0;
        
        if(($handle = fopen('database/csv/medical_representatives.csv', "r")) !== FALSE){
        	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        		if($row != 0) {

        			$medrep = MedicalRepresentative::where('email', $data[1])->withTrashed()->first();
        			if(!$medrep) {
		    			$this->command->info('writing row ' . $row . ' ' . $data[1]);
        				MedicalRepresentative::create([
        					'region_id' => $data[0],
        					'email' => $data[1],
        					'password' => $data[2],
        					'fullname' => $data[3],
        					'mobile' => $data[4],
        					'image_path' => $data[5],
        					'email_verified_at' => $data[6] != '' ? $data[6] : null,
        					'remember_token' => $data[7],
        					'deleted_at' => $data[8] != '' ? $data[8] : null,
        				]);
        			}
        			

        			// $item->save();
        		}

                $row++;

            }
            fclose($handle);
        }
    }
}
