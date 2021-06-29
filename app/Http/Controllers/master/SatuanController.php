<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class SatuanController extends Controller
{
    public function index(){

        $satuan = Satuan::get();

        return view('master.satuan.index', compact('satuan'));
    }

    public function save(Request $request)
    {
        $satuan = new Satuan;
        $satuan->nama = $request->nama;
        $satuan->save();
        Session::flash('success');
        return redirect()->back();
    }

    public function delete($id)
    {
        DB::table('satuan')->where('id', $id)->delete();
        Session::flash('delete');
        return redirect()->back();
    }

    public function edit($id)
    {
        $satuan = Satuan::find($id);
        return view('master.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        Satuan::where('id', $id)->update([
            'nama' => $request->nama,
        ]);
        Session::flash('update');
    }
}
