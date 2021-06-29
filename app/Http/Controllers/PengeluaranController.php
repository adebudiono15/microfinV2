<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianKredit;
use App\Models\PengeluaranBank;
use App\Models\PengeluaranLangsung;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index(){
        $pengeluaranlangsung = PengeluaranLangsung::get();
        $pengeluaranbank = PengeluaranBank::get();

        return view('master.pengeluaran.index',compact('pengeluaranlangsung', 'pengeluaranbank'));
    }

    public function detailpengeluaranlangsung($id){
        $pengeluaranlangsung = PengeluaranLangsung::find($id);
        $kode_supplier = $pengeluaranlangsung->supplier_id;
        $supplier = Supplier::find($kode_supplier);
        return view('master.pengeluaran.detailpengeluaranlangsung', compact('pengeluaranlangsung','supplier'));
    }

    public function detailpengeluaranbank ($id){
        $pengeluaranbank = PengeluaranBank::find($id);
        $kode_supplier = $pengeluaranbank->supplier_id;
        $supplier = Supplier::find($kode_supplier);
        return view('master.pengeluaran.detailpengeluaranbank', compact('pengeluaranbank','supplier'));
    }
}
