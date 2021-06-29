<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::get();
        $supplier = Supplier::get();
        $satuan = Satuan::get();
        $kategori = Kategori::get();

        return view('master.barang.index', compact('barang', 'satuan', 'supplier', 'kategori'));
    }

    public function save(Request $request)
    {
        $nama = $request->nama;
        $supplier = $request->supplier;
        $satuan = $request->satuan;
        $kategori = $request->kategori;

        // Co harga beli
        $hargabelisementara = $request->harga_beli;
        $harga_beli = str_replace([".", "Rp", " "], '', $hargabelisementara);
        // co harga jual
        $hargabelisementara = $request->harga_jual;
        $harga_jual = str_replace([".", "Rp", " "], '', $hargabelisementara);

        $stock_sem = $request->stock;
        $stock = str_replace([".", "Rp", " "], '', $stock_sem);

        // kode barang
        $firstInvoiceID = Barang::count('id');
        $secondInvoiceID = $firstInvoiceID + 1;
        $nomor = sprintf("%05d", $secondInvoiceID);
        $kode = "BMP1$nomor";

        if ($kategori == 1) {
            DB::table('barang')->insertGetId([
                'nama_barang' => $nama,
                'kode_barang' => $kode,
                'kategori_id' =>  $kategori,
                'satuan_id' => $satuan,
                'stock' => $stock,
                'supplier_id' => $supplier,
                'harga_beli' => 0,
                'harga_jual' => 0,
                'hb1' => $harga_beli,
                'hb100' => $harga_beli * 100,
                'hb250' => $harga_beli * 250,
                'hb500' => $harga_beli * 500,
                'hb1l' => $harga_beli * 1000,
                'hj1' => $harga_jual,
                'hj100' => $harga_jual * 100,
                'hj250' => $harga_jual * 250 - 10000,
                'hj500' => $harga_jual * 500 - 25000,
                'hj1l' => $harga_jual * 1000 - 50000,
                'status' => 1,
            ]);
            Session::flash('success');
            return redirect()->back();
        } else {
            DB::table('barang')->insertGetId([
                'nama_barang' => $nama,
                'kode_barang' => $kode,
                'satuan_id' => $satuan,
                'stock' => $stock,
                'kategori_id' =>  $kategori,
                'supplier_id' => $supplier,
                'harga_beli' => $harga_beli,
                'harga_jual' => $harga_jual,
                'hb1' => 0,
                'hb100' => 0,
                'hb250' => 0,
                'hb500' => 0,
                'hb1l' => 0,
                'hj1' => 0,
                'hj100' => 0,
                'hj250' => 0,
                'hj500' => 0,
                'hj1l' => 0,
                'status' => 2,
            ]);
            Session::flash('success');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        DB::table('barang')->where('id', $id)->delete();
        Session::flash('delete');
        return redirect()->back();
    }
}
