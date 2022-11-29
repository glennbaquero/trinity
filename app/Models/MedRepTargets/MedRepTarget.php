<?php

namespace App\Models\MedRepTargets;

use App\Extendables\BaseModel as Model;

use App\Models\Users\MedicalRepresentative;
use App\Models\Calls\Call;

use Carbon\Carbon;

class MedRepTarget extends Model
{


	const SALES = 1;
	const PATIENTS = 2;
    const DOCTORS = 3;
    const CALL_REACH = 4;
    const CALL_RATE = 5;

    /*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/

	public function medicalRepresentative()
	{
		return $this->belongsTo(MedicalRepresentative::class);
	}


    /*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/

    /**
     * Store/Update specified resource from storage
     * 
     * @param  Array $request
     * @param  object $item
     */
    public static function store($request, $item = null)
    {
        $vars = $request;

        if(!$item) {
            $item = static::create($vars); 
        } else {
            $item->update($vars);
        }

        return $item;        

    }

    /**
     * Formatting of reports
     * 
     * @param  Array $request
     * @param  int $id
     */
    public static function formatReport($request, $id)
    {

        $medRep = MedicalRepresentative::find($id);

    	if($request->sortType === 1) {
    		return self::formatByMonth($request, $medRep);
    	} else {
            return self::formatByQuarter($request, $medRep);
        }

    }

    /**
     * Format by month function
     * 
     * @param  Array $request
     * @param  int $id
     */
    public static function formatByMonth($request, $medRep) 
    {	
    	return array_merge(
            self::patientsAndDoctorsReport([$request->filter], $request->year, $medRep),
            self::salesReport([$request->filter], $request->year, true, $medRep),
            self::callReport([$request->filter], $request->year, $medRep)
        );
    }

    /**
     * Format quarterly function
     * 
     * @param  Array $request
     * @param  int $id
     */
    public static function formatByQuarter($request, $medRep) 
    {
        $filters = self::renderQuarter($request->filter);
        return array_merge(
            self::patientsAndDoctorsReport($filters, $request->year, $medRep),
            self::salesReport($filters, $request->year, false, $medRep),
            self::callReport($filters, $request->year, $medRep)
        );
    }


    /**
     * Generate patients reports
     * 
     * @param  Array $request
     * @param  int $id
     */
    public static function patientsAndDoctorsReport($months, $year, $medRep)
    {
    	$query = [
    		'year' => $year,
    		'medical_representative_id' => $medRep->id
    	];

    	$targetPatients = MedRepTarget::where($query)
                            ->where('type', MedRepTarget::PATIENTS)->whereIn('month', $months)->first();

        $targetDoctors = MedRepTarget::where($query)
                            ->where('type', MedRepTarget::DOCTORS)->whereIn('month', $months)->first();

        $actualPatients = $medRep->renderTotalPatients($months, $year);
        $patientsTarget = $targetPatients ? $targetPatients->value: 0;

        $totalPatients = $medRep->renderTotalPatients($months, $year, true);
        $prescribedCount = $medRep->renderTotalPatients($months, $year, true, true);

        $doctorsTarget = $targetDoctors ? $targetDoctors->value: 0;
        $actualDoctors = $medRep->renderTotalDoctors($months, $year);

    	return [
    		'patientsTarget' => $patientsTarget,
    		'actualPatients' => $actualPatients,
            'patientsPercentage' => $actualPatients ? self::calculatePercentage($actualPatients, $patientsTarget): "0%",
            'doctorsTarget' => $doctorsTarget,
            'actualDoctors' => $actualDoctors,
            'doctorsPercentage' => $actualDoctors ? self::calculatePercentage($doctorsTarget, $actualDoctors): "0%",

            'callEfficiency' => $totalPatients,
            'prescribedCount' => $prescribedCount,
    	];

    }

    /**
     * Generating call reports
     * 
     * @param  Array $months
     * @param  String $year
     * @param  Object $medRep
     */
    public static function callReport($months, $year, $medRep)
    {
        $now = Carbon::now();

        $query = [
            'year' => $year,
            'medical_representative_id' => $medRep->id
        ];

        $targetCallReach = MedRepTarget::where($query)
                            ->where('type', MedRepTarget::CALL_REACH)
                            ->whereIn('month', $months)->first();

        $targetCallRate = MedRepTarget::where($query)
                            ->where('type', MedRepTarget::CALL_RATE)
                            ->whereIn('month', $months)->first();

        $calls = $medRep->calls()
                            ->whereIn(\DB::raw('month(scheduled_date)'), $months)
                            ->where(\DB::raw('year(scheduled_date)'), $year);
                            
        
        $targetCallReach = $targetCallReach ? (int) $targetCallReach->value: 0;
        $actualCalls = $calls->where('status', 1)
                        ->where([ ['arrived_at', '!=', null], ['left_at', '!=', null] ])
                        ->count();

        $targetCallRate = $targetCallRate ? (int) $targetCallRate->value: 0;
        $callRate = $calls->count();

        return [

            'actualCalls' => $actualCalls,
            'targetCallReach' => $targetCallReach,
            'actualCallsPercentage' => $actualCalls ? self::calculatePercentage($actualCalls, $targetCallReach): "0%",
            
            'targetCallRate' => $targetCallRate,            
            'actualCallRate' => $callRate,
            'actualCallRatePercentage' => $callRate ? self::calculatePercentage($callRate, $targetCallRate): "0%",

            'doctors' => Call::filterDoctorsCall($calls->get(), $months, $year), 
        ];

    }


    /**
     * Generate sales reports
     * 
     * @param  Array $request
     * @param  int $id
     */
    public static function salesReport($months, $year, $monthly, $medRep)
    {
    	$salesQuery = [
    		'year' => $year,
    		'type' => MedRepTarget::SALES,
    		'medical_representative_id' => $medRep->id
    	];

    	$targetSales = MedRepTarget::where($salesQuery)->whereIn('month', $months)->sum('value');


        $sales = $medRep->renderTotalSales($months, $year);
        $incentivePercentage = $monthly ? self::monthlySalesIncentive($sales): self::quarterlySalesIncentive($sales);

    	return  [
	    	'sales' => 'Php ' . number_format($sales, 2, '.', ','),
	    	'salesPercentage' =>  $targetSales ? self::calculatePercentage($sales, $targetSales): "0%",
	    	'target' => $targetSales ? 'Php ' . number_format($targetSales, 2, '.', ','): 0,
	    	'incentivePercentage' => $incentivePercentage,
	    	'incentives' => self::calculateIncentives($incentivePercentage, $sales),
    	];
    }

    /**
     * Calculate sales percentage
     * 
     * @param  int sales
     * @param  int $target
     */    
    public static function calculatePercentage($actual, $target)
    {
        if(!$actual || !$target) {
            return "0%";
        }

    	$percentage = ($actual / $target) * 100;
    	if($percentage > 100) {
    		return "100%";
    	}
    	return round($percentage, 2) . "%";
    } 


    /**
     * Calculate total incentives
     * 
     * @param  int $percentage
     * @param  int $sales
     */
    public static function calculateIncentives($percentage, $sales)
    {
    	$percentage = $percentage / 100;
    	$totalIncentives = $percentage * $sales;
    	return 'Php ' . number_format($totalIncentives, 2, '.', ',');
    }

    /**
     * Get monthly incentives
     * 
     * @param  int $sales
     */
    public static function monthlySalesIncentive($sales)
    {
	
    	$percentage = null;

    	switch ($sales) {
    		case $sales < 1000000 :
    			$percentage = 1;
    			break;
    		
    		case $sales >= 1000000 || $sales <= 1500000 :
    			$percentage = 1.20;
    			break;
    		
    		case $sales > 1500000 : 
    			$percentage = 1.30;
    			break;

    		default:
    			# code...
    			break;
    	}

    	return $percentage;

    }

    /**
     * Get quarterly incentives
     * 
     * @param  int $sales
     */
    public static function quarterlySalesIncentive($sales)
    {
    
        $percentage = null;

        switch ($sales) {
            case $sales < 3000000 :
                $percentage = 0.20;
                break;
            
            case $sales >= 3000000 || $sales <= 4500000 :
                $percentage = 0.30;
                break;
            
            case $sales > 4500000 : 
                $percentage = 0.40;
                break;

            default:
                # code...
                break;
        }

        return $percentage;

    }

    /**
     * Render months per quarter
     * 
     * @param  int $type
     */
    public static function renderQuarter($type)
    {
        switch ($type) {
            case $type == 1:
                return [1, 2, 3];
            break;

            case $type == 2:
                return [4, 5, 6];
            break;            

            case $type == 3:
                return [7, 8, 9];
            break;

            case $type == 4:
                return [10, 11, 12];
            break;

        }
    }

    /**
     * Generate months
     * 
     */
	public static function generateMonths()
	{

		return collect([
			['name' => 'January', 'value' => 1],
			['name' => 'February', 'value' => 2],
			['name' => 'March', 'value' => 3],
			['name' => 'April', 'value' => 4],
			['name' => 'May', 'value' => 5],
			['name' => 'June', 'value' => 6],
			['name' => 'July', 'value' => 7],
			['name' => 'August', 'value' => 8],
			['name' => 'September', 'value' => 9],
			['name' => 'October', 'value' => 10],
			['name' => 'November', 'value' => 11],
			['name' => 'December', 'value' => 12],									
		]);	

	}


    /**
     * Generate years
     * 
     */
    public static function generateYears()
    {
        $years = MedRepTarget::selectRaw(\DB::raw('year(created_at) as years'))
                            ->groupBy('years')
                            ->orderBy('years', 'desc')
                            ->get();        
        return $years;
    }

    /**
     * Generate target type
     * 
     */
	public static function generateTargetType()
	{
		return collect([
			['name' => 'Sales', 'value' => MedRepTarget::SALES],
			['name' => 'Patients', 'value' => MedRepTarget::PATIENTS],
            ['name' => 'Doctors', 'value' => MedRepTarget::DOCTORS],
            ['name' => 'Call Reach', 'value' => MedRepTarget::CALL_REACH],
            ['name' => 'Call Rate', 'value' => MedRepTarget::CALL_RATE],            
		]);
	}

    /*
	|--------------------------------------------------------------------------
	| @Renders
	|--------------------------------------------------------------------------
	*/	

    /**
     * Render target type
     *
     * @return  array
     */
	public function renderType()
	{
		foreach (self::generateTargetType() as $key => $value) {
			if($this->type === $value['value']) {
				return $value;
			} 
		}
	}

    /**
     * Render month name
     * 
     * @return array
     */
	public function renderMonth()
	{
		foreach (self::generateMonths() as $key => $value) {
			if($this->month === $value['value']) {
				return $value;
			}
		}
	}

    /**
     * Render formatted value
     * 
     * @return string
     */
	public function renderFormattedValue()
	{
		if($this->value) {
			return number_format($this->value, 2, '.', ',');
		}
	}

    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderUpdateUrl()
    {
        return route('admin.medrep-targets.update', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.medrep-targets.archive', $this->id);
    }


    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.medrep-targets.restore', $this->id);
    }

    /*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/	

    /**
     * Checker if target entry already exist
     * 
     * @param  array $request [description]
     * @param  int $id
     * @return Boolean
     */
	public static function alreadyExists($request, $id = null, $medRepID)
	{	

		$query = [
			'year' => $request->year,
			'month' => $request->month,
			'type' => $request->type,
            'medical_representative_id' => $medRepID
		];


		$target = MedRepTarget::where($query);

        if($id) {
            if($target->first() && $id) {
                return $target->first()->id == $id ? false: true;
            }
        }

		if($target->exists()) {
			return true;
		}
		return false;
	}

}
