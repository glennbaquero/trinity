<?php

namespace App\Http\Controllers\Admin\Consultations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Consultations\Consultation;

class ConsultationController extends Controller
{
	public function index()
	{
		return view('admin.consultations.index');
	}

	public function show($id, $consultation_number)
	{	

		$consultation = Consultation::find($id);

		return view('admin.consultations.show', [
			'consultation' => $consultation
		]);
	}
}
