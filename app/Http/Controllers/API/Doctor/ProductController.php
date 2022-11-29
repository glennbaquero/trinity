<?php

namespace App\Http\Controllers\API\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Fetch all doctor's linked product
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $doctor = request()->user();

        $data = $doctor->specialization->products();
        if(request()->sort) {
            if(request()->sort == 1) {
                $data->orderBy('price', 'asc');
            } elseif (request()->sort == 2) {
                $data->orderBy('price', 'desc');
            } elseif (request()->sort == 3) {
                $data->orderBy('brand_name', 'asc');
            }
        } 
        if(request()->search) {
            $data->where('brand_name', 'like', '%' . request()->search . '%')
                ->orWhere('generic_name', 'like', '%'.request()->search.'%')
                ->orWhere('name', 'like', '%'.$this->request->product_name.'%');
        }

        return response()->json([
            'data' => $data->paginate(10),
        ]);
    }
}
