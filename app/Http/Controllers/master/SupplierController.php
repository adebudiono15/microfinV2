<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class SupplierController extends Controller
{
    public function index()
    {
        $supplier = Supplier::get();

        return view('master.supplier.index', compact('supplier'));
    }

    public function save(Request $request)
    {

        // Kode
        $firstInvoiceID = Supplier::count('id');
        $secondInvoiceID = $firstInvoiceID + 1;
        $nomor = sprintf("%05d", $secondInvoiceID);
        $kode = "SMP1$nomor";

        $supplier = new Supplier;
        $supplier->kode_supplier = $kode;
        $supplier->nama = $request->nama;
        $supplier->alamat = $request->alamat;
        $supplier->telepon = $request->telepon;
        $supplier->email = $request->email;
        $supplier->contact_person = $request->contact_person;
        $supplier->save();
        Session::flash('success');
        return redirect()->back();
    }

    public function delete($id)
    {
        DB::table('supplier')->where('id', $id)->delete();
        Session::flash('delete');
        return redirect()->back();
    }

    public function detail($id)
    {
        $supplier = Supplier::find($id);

        return view('master.supplier.detail', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return view('master.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        Supplier::where('id', $id)->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'contact_person' => $request->contact_person,
        ]);
        Session::flash('update');
    }
}
