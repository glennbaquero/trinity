<?php

use Illuminate\Database\Seeder;

use App\Models\Consultations\Consultation;

class ConsultationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 0;
        if(($handle = fopen('database/csv/consultations.csv', "r")) !== FALSE){ // Check if CSV file exists
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { // Parse data inside the file
                if ($row > 0) { // row check

                    $this->command->info('Seeding record ' . $row . ' ' . $data[3]); // Traces seeded rows in terminal

                    // Seed table with csv data
                    $item = new Consultation(); 
                    $item->user_id = $data[0]; 
                    $item->doctor_id = $data[1]; 
                    $item->scheduled_id = $data[2]; 
                    $item->consultation_number = $data[3]; 
                    $item->type = $data[4]; 
                    $item->consultation_fee = $data[5]; 
                    $item->start_time = $data[6]; 
                    $item->end_time = $data[7]; 
                    $item->additional_notes = $data[8]; 
                    $item->status = $data[9]; 
                    $item->save();
                }
                $row++;
            }
            fclose($handle);
        }
    }
}
