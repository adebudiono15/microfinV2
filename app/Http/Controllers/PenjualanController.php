<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Customer;
use App\Models\HistoryPiutang;
use App\Models\PendapatanBank;
use App\Models\PendapatanLangsung;
use App\Models\Penjualan;
use App\Models\PenjualanKredit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PenjualanController extends Controller
{
    public function index()
    {

        $penjualan_langsung = Penjualan::get();
        $penjualan_kredit = PenjualanKredit::get();

        return view('master.penjualan.index', compact('penjualan_langsung', 'penjualan_kredit'));
    }

    public function detailPenjualanLangsung($id)
    {

        $penjualan_langsung = Penjualan::find($id);
        return view('master.penjualan.detailPenjualanLangsung', compact('penjualan_langsung'));
    }
    public function detailPenjualanKredit($id)
    {
        $bank = Bank::get();
        $penjualan_kredit = PenjualanKredit::find($id);
        return view('master.penjualan.detailPenjualanKredit', compact('penjualan_kredit', 'bank'));
    }

    public function deletePenjualanLangsung($id)
    {
        DB::table('penjualan')->where('id', $id)->delete();
        Session::flash('delete');
        return redirect()->back();
    }
    public function deletePenjualanKredit($id)
    {
        DB::table('penjualan_kredit')->where('id', $id)->delete();
        Session::flash('delete');
        return redirect()->back();
    }


    public function update(Request $request)
    {
        $customer = $request->customer;
        $nama_customer= Customer::find($customer);
        $girobank = $request->girobank;
        $transferbank = $request->transferbank;

        $total_sisa = $request->total_sisa;
        $metode = $request->metode;
        $bayar_sementara = $request->bayar;
        $bayar = str_replace([".", "Rp", " "], '', $bayar_sementara);
        $id_piutang = $request->id_piutang;

        // Kode inv
        $firstInvoiceID = HistoryPiutang::whereDay('created_at', date('d'))->count('id');
        $secondInvoiceID = $firstInvoiceID + 1;
        $nomor = sprintf("%03d", $secondInvoiceID);
        $tanggal = date('Ymd');
        $kode_piutang = "MPMP1/INV/PNJ-K/$tanggal/$nomor";

        if (!$metode) {
            Session::flash('detail');
            return redirect()->back();
        }

        // if ($bayar > $total_sisa) {
        //     \Session()->flash('lebih');
        //     return redirect()->back();
        // } else {
        $pj = PenjualanKredit::find($id_piutang);
        $sisa = $pj->sisa;
        $data = $sisa - $bayar;

        PenjualanKredit::where('id', $id_piutang)->update([
            'sisa' => $data
        ]);

        HistoryPiutang::insert([
            'penjualan_kredit' => $id_piutang,
            'kode_piutang' =>  $kode_piutang,
            'metode' => $metode,
            'total_pembayaran' =>  $bayar,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($metode == 'Transfer') {
            PendapatanBank::insert([
                'kode_pendapatan_bank' => $kode_piutang,
                'tanggal' => date('Y-m-d'),
                'jenis_pendapatan' => "Pendapatan Piutang",
                'keterangan' => 'Transfer ' . $nama_customer->nama,
                'customer_id' => $customer,
                'bank' => $transferbank,
                'jumlah_pendapatan' =>  $bayar,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($metode == 'Giro') {
            PendapatanBank::insert([
                'kode_pendapatan_bank' => $kode_piutang,
                'tanggal' => date('Y-m-d'),
                'jenis_pendapatan' => "Pendapatan Piutang",
                'keterangan' => 'Transfer ' . $nama_customer->nama,
                'customer_id' => $customer,
                'bank' => $girobank,
                'jumlah_pendapatan' =>  $bayar,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($metode == 'Cash') {
            PendapatanLangsung::insert([
                'kode_pendapatan_tunai' => $kode_piutang,
                'tanggal' => Carbon::now(),
                'jenis_pendapatan' => "Pendapatan Piutang",
                'keterangan' => 'Transfer ' . $nama_customer->nama,
                'customer_id' => $customer,
                'jumlah_pendapatan' =>  $bayar,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
        }
        \Session()->flash('update');
        return redirect()->back();
        // }
    }
}
