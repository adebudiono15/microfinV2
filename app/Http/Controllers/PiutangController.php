<?php

namespace App\Http\Controllers;

use App\Models\PenjualanKredit;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    public function index(){
        $piutang = PenjualanKredit::get();
       return view('master.piutang.index', compact('piutang'));
    }
}
