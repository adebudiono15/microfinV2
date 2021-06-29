<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\HistoryHutang;
use App\Models\Pembelian;
use App\Models\PembelianKredit;
use App\Models\PembelianKreditLine;
use App\Models\PembelianLine;
use App\Models\PengeluaranBank;
use App\Models\PengeluaranLangsung;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PembelianController extends Controller
{
    public function index()
    {

        $pembelian_langsung = Pembelian::get();
        $pembelian_kredit = PembelianKredit::get();
        $supplier = Supplier::get();
        $tanggalsekarang =  date('Y-m-d', strtotime('+30 days'));
        $barang = Barang::get();

        return view('master.pembelian.index', compact('pembelian_langsung', 'pembelian_kredit', 'supplier', 'tanggalsekarang', 'barang'));
    }

    public function get_barang($id)
    {
        $item = Barang::where('id', $id)->first();

        return response()->json([
            'data' => $item
        ]);
    }

    public function savepembelianlangsung(Request $request)
    {

        try {
            $metode = $request->metode;
            $inv_pembelian = $request->inv_pembelian;
            $nama = $request->nama;
            $supplier_id = $request->supplier_id;
            $harga = $request->harga;
            $qty = $request->qty;
            $girobank = $request->girobank;
            $transferbank = $request->transferbank;

            // get supplier
            $supplier = Supplier::find($supplier_id);

            // Transfer
            if ($metode == 'Transfer') {
                $header = Pembelian::insertGetId([
                    'no_struk' => $inv_pembelian,
                    'supplier_id' => $supplier->id,
                    'metode_pembayaran' => $metode,
                    'bank' => $transferbank,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ]);
            }

            // Giro
            if ($metode == 'Giro') {
                $header = Pembelian::insertGetId([
                    'no_struk' => $inv_pembelian,
                    'supplier_id' => $supplier->id,
                    'metode_pembayaran' => $metode,
                    'bank' => $girobank,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ]);
            }

            // Cash
            if ($metode == 'Cash') {
                $header = Pembelian::insertGetId([
                    'no_struk' => $inv_pembelian,
                    'supplier_id' => $supplier->id,
                    'metode_pembayaran' => $metode,
                    'bank' => '-',
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ]);
            }

            // Pot.Nota
            if ($metode == 'Pot. Nota') {
                $header = Pembelian::insertGetId([
                    'no_struk' => $inv_pembelian,
                    'supplier_id' => $supplier->id,
                    'metode_pembayaran' => $metode,
                    'bank' => '-',
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ]);
            }

            foreach ($nama as $e => $nm) {
                PembelianLine::insert([
                    'pembelian' => $header,
                    'nama' => $nm,
                    'harga_beli' => $harga[$e],
                    'qty' => $qty[$e],
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                    'grand_total' => (int)$qty[$e] * (int) $harga[$e]
                ]);
            }
            $sum_total = PembelianLine::where('pembelian', $header)->sum('grand_total');
            Pembelian::where('id', $header)->update([
                'grand_total' => $sum_total,
            ]);

            foreach ($qty as $e => $qt) {
            if ($qt == 0) {
                continue;
            }
                $stock_barang = Barang::where('id', $nama[$e])->first();
                $qty_now = $stock_barang->stock;
                $qty_new = $qty_now + $qty[$e];
                Barang::where('id', $nama[$e])->update([
                    'stock'=>$qty_new
                    ]);
            }

        } catch (\Throwable $e) {
            \Session()->flash('gagal', $e->getMessage());
        }
        \Session()->flash('success');
        return redirect()->back();
    }

    public function detailPembelianlangsung($id)
    {

        $pembelian_langsung = Pembelian::find($id);
        return view('master.pembelian.detailPembelianLangsung', compact('pembelian_langsung'));
    }

    public function deletePembelianlangsung($id){
        DB::table('pembelian')->where('id', $id)->delete();
        Session::flash('delete');
        return redirect()->back();
    }


    public function savepembeliankredit(Request $request)
    {

        try {
            $inv_pembelian = $request->inv_pembelian;
            $nama = $request->nama;
            $supplier_id = $request->supplier_id;
            $harga = $request->harga;
            $qty = $request->qty;

            // get supplier
            $supplier = Supplier::find($supplier_id);

                $header = PembelianKredit::insertGetId([
                    'no_struk' => $inv_pembelian,
                    'supplier_id' => $supplier->id,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ]);

            foreach ($nama as $e => $nm) {
                PembelianKreditLine::insert([
                    'pembelian_kredit' => $header,
                    'nama' => $nm,
                    'harga_beli' => $harga[$e],
                    'qty' => $qty[$e],
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                    'grand_total' => (int)$qty[$e] * (int) $harga[$e]
                ]);
            }
            $sum_total = PembelianKreditLine::where('pembelian_kredit', $header)->sum('grand_total');
            PembelianKredit::where('id', $header)->update([
                'grand_total' => $sum_total,
                'sisa' => $sum_total,
            ]);

            foreach ($qty as $e => $qt) {
            if ($qt == 0) {
                continue;
            }
                $stock_barang = Barang::where('id', $nama[$e])->first();
                $qty_now = $stock_barang->stock;
                $qty_new = $qty_now + $qty[$e];
                Barang::where('id', $nama[$e])->update([
                    'stock'=>$qty_new
                    ]);
            }

        } catch (\Throwable $e) {
            \Session()->flash('gagal', $e->getMessage());
        }
        \Session()->flash('success');
        return redirect()->back();
    }

    public function detailPembeliankredit($id)
    {
        $bank = Bank::get();
        $pembelian_kredit = PembelianKredit::find($id);
        return view('master.pembelian.detailPembelianKredit', compact('pembelian_kredit','bank'));
    }

    public function update(Request $request)
    {
        $supplier = $request->supplier;
        $nama_supplier= Supplier::find($supplier);
        $girobank = $request->girobank;
        $transferbank = $request->transferbank;

        $total_sisa = $request->total_sisa;
        $metode = $request->metode;
        $bayar_sementara = $request->bayar;
        $bayar = str_replace([".", "Rp", " "], '', $bayar_sementara);
        $id_hutang = $request->id_hutang;

        // Kode inv
        $firstInvoiceID = HistoryHutang::whereDay('created_at', date('d'))->count('id');
        $secondInvoiceID = $firstInvoiceID + 1;
        $nomor = sprintf("%03d", $secondInvoiceID);
        $tanggal = date('Ymd');
        $kode_hutang = "KHMP1/INV/PNJ-K/$tanggal/$nomor";

        if (!$metode) {
            Session::flash('detail');
            return redirect()->back();
        }

        $pj = PembelianKredit::find($id_hutang);
        $sisa = $pj->sisa;
        $data = $sisa - $bayar;

        PembelianKredit::where('id', $id_hutang)->update([
            'sisa' => $data
        ]);

        HistoryHutang::insert([
            'pembelian_kredit' => $id_hutang,
            'kode_hutang' =>  $kode_hutang,
            'metode' => $metode,
            'total_pembayaran' =>  $bayar,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($metode == 'Transfer') {
            PengeluaranBank::insert([
                'kode_pengeluaran_bank' => $kode_hutang,
                'tanggal' => date('Y-m-d'),
                'jenis_pengeluaran' => "Pengeluaran Hutang",
                'keterangan' => 'Transfer ' . $nama_supplier->nama,
                'supplier_id' => $supplier,
                'bank' => $transferbank,
                'jumlah_pengeluaran' =>  $bayar,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($metode == 'Giro') {
            PengeluaranBank::insert([
                'kode_pengeluaran_bank' => $kode_hutang,
                'tanggal' => date('Y-m-d'),
                'jenis_pengeluaran' => "Pengeluaran Hutang",
                'keterangan' => 'Transfer ' . $nama_supplier->nama,
                'supplier_id' => $supplier,
                'bank' => $girobank,
                'jumlah_pengeluaran' =>  $bayar,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($metode == 'Cash') {
            PengeluaranLangsung::insert([
                'kode_pengeluaran_tunai' => $kode_hutang,
                'tanggal' => Carbon::now(),
                'jenis_pengeluaran' => "Pengeluaran Piutang",
                'keterangan' => 'Cash ' . $nama_supplier->nama,
                'supplier_id' => $supplier,
                'jumlah_pengeluaran' =>  $bayar,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
        }
        \Session()->flash('update');
        return redirect()->back();
        // }
    }
}
