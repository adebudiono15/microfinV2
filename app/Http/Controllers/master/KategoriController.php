<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class KategoriController extends Controller
{
     public function index(){

        $kategori = Kategori::get();

        return view('master.kategori.index', compact('kategori'));
    }

    public function save(Request $request)
    {
        $kategori = new Kategori;
        $kategori->nama = $request->nama;
        $kategori->save();
        Session::flash('success');
        return redirect()->back();
    }

    public function delete($id)
    {
        DB::table('kategori')->where('id', $id)->delete();
        Session::flash('delete');
        return redirect()->back();
    }

    public function edit($id)
    {
        $kategori = Kategori::find($id);
        return view('master.kateo$kategori.edit', compact('kateo$kategori'));
    }

    public function update(Request $request, $id)
    {
        Kategori::where('id', $id)->update([
            'nama' => $request->nama,
        ]);
        Session::flash('update');
    }
}
