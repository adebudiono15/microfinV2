<?php

namespace App\Http\Controllers\master;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class CustomerController extends Controller
{
   public function index(){
       $customer = Customer::get();
       return view('master.customer.index', compact('customer'));
   }

   public function save(Request $request){

         // Kode inv
         $firstInvoiceID = Customer::whereDay('created_at', date('d'))->count('id');
         $secondInvoiceID = $firstInvoiceID + 1;
         $nomor = sprintf("%03d", $secondInvoiceID);
         $tanggal = date('Ymd');
         $kode_customer = "CSMP1/$tanggal/$nomor";

         $customer = new Customer;

         $customer->nama = $request->nama;
         $customer->kode_customer = $kode_customer;
         $customer->alamat = $request->alamat;
         $customer->telepon = $request->telepon;
         $customer->email = $request->email;
         $customer->contact_person = $request->contact_person;
         $customer->save();
         Session::flash('success');
         return redirect()->back();
   }


    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('master.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        Customer::where('id', $id)->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'contact_person' => $request->contact_person,
        ]);
        Session::flash('update');
    }

    public function detail($id)
    {
        $customer = Customer::find($id);

        return view('master.customer.detail', compact('customer'));
    }
}
