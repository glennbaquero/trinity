<?php

namespace App\Http\Controllers\Admin\DoctorReviews;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Reviews\DoctorReview;
use App\Models\Users\Doctor;

class DoctorReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = Doctor::get();
        return view('admin.doctor-reviews.index', [
            'doctors' => $doctors
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = DoctorReview::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived review #{$item->id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = DoctorReview::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored review #{$item->id}",
        ]);
    }

}
