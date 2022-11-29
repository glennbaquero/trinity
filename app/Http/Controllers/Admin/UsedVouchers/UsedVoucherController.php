<?php

namespace App\Http\Controllers\Admin\UsedVouchers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsedVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.used-vouchers.index');
    }
}
