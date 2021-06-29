<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Divisi;
use App\Models\Migrasi;
use App\Models\MigrasiLine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MigrasiController extends Controller
{
    public function index()
    {
        $migrasi = Http::get('https://dpsalim.com/datamigrasi/api/migrasimp1');
        $response = json_decode($migrasi);
        $migrasimasuk = Http::get('https://dpsalim.com/datamigrasi/api/migrasimasukmp1');
        $responsemigrasimasuk= json_decode($migrasimasuk);
        $migrasikeluar = Http::get('https://dpsalim.com/datamigrasi/api/migrasikeluarmp1');
        $responsemigrasikeluar= json_decode($migrasikeluar);
        $divisi = Divisi::get();
        $barang = Barang::get();

        return view('master.migrasi.index', compact('response', 'divisi', 'barang','responsemigrasimasuk','responsemigrasikeluar'));
    }

    public function savemigrasi(Request $request)
    {
        try {
            $nama = $request->nama;
            $divisi = $request->divisi;
            $qty = $request->qty;

            // Find barang
            $barang = Barang::find($nama);

            // Kode
            $tanggal = date('Ymd');
            $nomor = date('sh');
            $kode = "MP1M/$tanggal/$nomor";


            foreach ($nama as $e => $nm) {
                $line = Http::post('https://dpsalim.com/datamigrasi/api/postline',[
                    'migrasi' => $kode,
                    'dari' => 'Marhaban Perfume 1',
                    'ke' => $divisi,
                    'nama' => $nm,
                    'qty' => $qty[$e],
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
            \Session()->flash('success');
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function updatesetuju($id){
        $data =  Http::put('https://dpsalim.com/datamigrasi/api/migrasiupdatekeluarmp1',
        [
            'id' => $id
        ]);

        \Session()->flash('setujumigrasi');
        return redirect()->back();
    }
}
