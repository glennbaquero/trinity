<?php

namespace App\Exports\Reports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use App\Models\Consultations\Consultation;
use App\Models\Users\Doctor;

class DeclinedConsultationReport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
	protected $requestData;

	public function __construct($requests)
    {
        $this->requestData = $requests;
    }


    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            BeforeSheet::class => function(BeforeSheet $event) {

                $event->sheet->setCellValue('A1', 'Report Type');
                $event->sheet->setCellValue('B1', 'Declined Consultations Report');

                $event->sheet->setCellValue('A3', 'Start Date');
                $event->sheet->setCellValue('B3', $this->requestData['date_range.start_date']);

                $event->sheet->setCellValue('A4', 'End Date');
                $event->sheet->setCellValue('B4', $this->requestData['date_range.end_date']);

                $event->sheet->setCellValue('A5', '');
                $event->sheet->setCellValue('A6', '');

            },
        ];
    }

    /**
     * Setting up headings
     * 
     * @return array
     */
    public function headings() : array
    {  

    	return [
            'Doctor',
            'Patient',
            'Consultation Number',
            'Date of Consultation',
            'Consultation Type',
            'Status',
            'Start Time',
            'End Time',
    	];

    }  

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$collections = [];

        if($this->requestData['doctor_id'] == 0.1) {
            $consultations = Consultation::where('status', Consultation::DISAPPROVED)->whereBetween('created_at', [$this->requestData['date_range.start_date'], $this->requestData['date_range.end_date']])->get();
        } else {
    	   $consultations = Consultation::where('status', Consultation::DISAPPROVED)->whereBetween('created_at', [$this->requestData['date_range.start_date'], $this->requestData['date_range.end_date']])->where('doctor_id', $this->requestData['doctor_id'])->get();
        }

		$collections = collect($this->formatColumns($consultations));
		
		return $collections;
    }


    /**
     * Formatting columns
     * 
     * @return array
     */
    public function formatColumns($consultations, $reports = [])
    {
    	foreach ($consultations as $key => $consultation) {
            if($consultation->doctor) {
                $columns = [
                    'doctor_id' => $consultation->doctor->fullname,
                    'user_id' => $consultation->user->renderFullName(),
                    'consultation_number' => $consultation->consultation_number,
                    'date' => $consultation->renderShortDate($consultation->date),
                    'type' => $consultation->renderType(),
                    'status' => $consultation->renderStatus(),
                    'start_time' => $consultation->start_time,
                    'end_time' => $consultation->end_time,
                ];
                array_push($reports, $columns);
            }
    	}
    	return $reports;
    }

}
