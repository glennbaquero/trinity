<?php 

namespace App\Helpers;

use Spatie\Activitylog\Traits\LogsActivity;

class CustomLogHelper {

    use LogsActivity;

    /**
     * Create logs
     * 
     * @param  int $causer
     * @param  object $performedOn
     * @param  string $logMessage 
     */
	public static function createLog($causer, $performedOn, $logMessage)
	{
        activity()
            ->causedBy($causer)
            ->performedOn($performedOn)
            ->log($logMessage);
	}

}