<?php

namespace App\Http\Controllers\Admin\MedRepTargets;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\MedRepTargets\MedRepTargetStorePost;

use App\Models\MedRepTargets\MedRepTarget;

class MedRepTargetController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MedRepTargetStorePost $request, $id)
    {
        if(MedRepTarget::alreadyExists($request, null, $id)) {
            return response()->json([
                'errors' => ['target' => ['Target entry already exists']],
                'message' => 'Invalid entry']
                , 422);
        }

        $vars = $request->except([]);
        $vars['medical_representative_id'] = $id;

        /** Start transaction */
        \DB::beginTransaction();

            /** Store med rep target */
            $medRepTarget = MedRepTarget::store($vars);

        /** End transaction */
        \DB::commit();

        $message = "You have successfully created a target";

        return response()->json([
            'message' => $message,
        ]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MedRepTargetStorePost $request, $id)
    {
        $medRepTarget = MedRepTarget::find($id);

        if(MedRepTarget::alreadyExists($request, $id, $medRepTarget->medical_representative_id)) {
            return response()->json([
                'errors' => ['target' => ['Target entry already exists']],
                'message' => 'Invalid entry']
                , 422);
        }

        $message = "You have successfully updated a target entry";

        $vars = $request->except([]);

        /** Start transaction */
        \DB::beginTransaction();

            /** Store med rep target */
            $medRepTarget = MedRepTarget::store($vars, $medRepTarget);

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => $message,
        ]);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $medRepTarget = MedRepTarget::find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Soft delete medRepTarget */
            $medRepTarget->archive();

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => "You have successfully archived a target entry",
        ]);
    }

    /**
     * Restore the specified resource from storage
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {

        $medRepTarget = MedRepTarget::onlyTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Recover/Restore medRepTarget */
            $medRepTarget->unarchive();

        /** End transaction */
        \DB::commit();        

        return response()->json([
            'message' => "You have successfully restored a target entry",
        ]);

    }
}
