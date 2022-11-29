<?php

use Illuminate\Database\Seeder;

use App\Models\Users\Doctor;

class DoctorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 0;
        
        if(($handle = fopen('database/csv/doctors.csv', "r")) !== FALSE){
        	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        		if($row != 0) {

        			$doctor = Doctor::where('email', $data[2])->withTrashed()->first();
        			if(!$doctor) {
		    			$this->command->info('writing row ' . $row . ' ' . $data[2]);
        				Doctor::create([
        					'medical_representative_id' => $data[0],
        					'specialization_id' => $data[1],
        					'email' => $data[2],
        					'password' => $data[3],
        					'first_name' => $data[4],
        					'last_name' => $data[5],
        					'mobile_number' => $data[6],
        					'qr_id' => $data[7],
        					'class' => $data[8],
        					'clinic_address' => $data[9],
        					'clinic_hours' => $data[10],
        					'brand_adaption_notes' => $data[11],
        					'email_verified_at' => $data[12] != '' ? $data[12] : null,
        					'qr_code_path' => $data[13],
        					'image_path' => $data[14],
        					'status' => $data[15],
        					'remember_token' => $data[16],
        					'deleted_at' => $data[17] != '' ? $data[17] : null,
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
