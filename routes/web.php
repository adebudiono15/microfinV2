<?php

// Master
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\master\KategoriController;
use App\Http\Controllers\master\BarangController;
use App\Http\Controllers\master\CustomerController;
use App\Http\Controllers\master\SatuanController;
use App\Http\Controllers\master\SupplierController;
use App\Http\Controllers\MigrasiController;
use App\Http\Controllers\PembelianController;
// Pendapatan
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\PengeluaranController;
// Penjualan
use App\Http\Controllers\PenjualanController;
// Piutang
use App\Http\Controllers\PiutangController;
// POS
use App\Http\Livewire\Cart;

use Illuminate\Support\Facades\Route;


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// MASTER
// Satuan
Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan');
Route::post('/save-satuan', [SatuanController::class, 'save'])->name('save-satuan');
Route::delete('delete-satuan/{id}', [SatuanController::class, 'delete'])->name('delete-satuan');
Route::get('{id}/edit-satuan', [SatuanController::class, 'edit'])->name('edit-satuan');
Route::patch('satuan/update/{id}', [SatuanController::class, 'update'])->name('update-satuan');

// Supplier
Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
Route::post('/save-supplier', [SupplierController::class, 'save'])->name('save-supplier');
Route::delete('delete-supplier/{id}', [SupplierController::class, 'delete'])->name('delete-supplier');
Route::get('{id}/detail-supplier', [SupplierController::class, 'detail'])->name('detail-supplier');
Route::get('{id}/edit-supplier', [SupplierController::class, 'edit'])->name('edit-supplier');
Route::patch('supplier/update/{id}', [SupplierController::class, 'update'])->name('update-supplier');


// Kategori
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
Route::post('/save-kategori', [KategoriController::class, 'save'])->name('save-kategori');
Route::delete('delete-kategori/{id}', [KategoriController::class, 'delete'])->name('delete-kategori');
Route::get('{id}/edit-kategori', [KategoriController::class, 'edit'])->name('edit-kategori');
Route::patch('kategori/update/{id}', [KategoriController::class, 'update'])->name('update-kategori');

// Barang
Route::get('/barang', [BarangController::class, 'index'])->name('barang');
Route::post('/save-barang', [BarangController::class, 'save'])->name('save-barang');
Route::delete('delete-barang/{id}', [BarangController::class, 'delete'])->name('delete-barang');

// Customer
Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
Route::post('/save-customer', [CustomerController::class, 'save'])->name('save-customer');
Route::delete('delete-customer/{id}', [CustomerController::class, 'delete'])->name('delete-customer');
Route::get('{id}/detail-customer', [CustomerController::class, 'detail'])->name('detail-customer');
Route::get('{id}/edit-customer', [CustomerController::class, 'edit'])->name('edit-customer');
Route::patch('customer/update/{id}', [CustomerController::class, 'update'])->name('update-customer');

// LAST MASTER

// TRANSAKSI
// Penjualan
Route::get('/dashboard-penjualan', [PenjualanController::class, 'index'])->name('dashboard-penjualan');
// Penjualan Langsug
Route::get('{id}/detail-penjualan-langsung', [PenjualanController::class, 'detailPenjualanLangsung'])->name('detail-penjualan-langsung');
Route::delete('delete-penjualan-langsung/{id}', [PenjualanController::class, 'deletePenjualanLangsung'])->name('delete-penjualan-langsung');
// Penjualan kredit
Route::get('{id}/detail-penjualan-kredit', [PenjualanController::class, 'detailPenjualanKredit'])->name('detail-penjualan-kredit');
Route::delete('delete-penjualan-kredit/{id}', [PenjualanController::class, 'deletePenjualanKredit'])->name('delete-penjualan-kredit');
Route::put('update-piutang/{id}', [PenjualanController::class, 'update']);
// pembelian
Route::get('/dashboard-pembelian', [PembelianController::class, 'index'])->name('dashboard-pembelian');
Route::get('barang/ajax/{id}',  [PembelianController::class, 'get_barang']);
// pembelian lansung
Route::post('save-pembelianlangsung', [PembelianController::class, 'savepembelianlangsung'])->name('save-pembelianlangsung');
Route::get('{id}/detail-pembelian-langsung', [PembelianController::class, 'detailPembelianlangsung'])->name('detail-pembelian-langsung');
Route::delete('delete-pembelian-langsung/{id}', [PembelianController::class, 'deletePembelianlangsung'])->name('delete-pembelian-langsung');
// Pembeliankredit
Route::post('save-pembeliankredit', [PembelianController::class, 'savepembeliankredit'])->name('save-pembeliankredit');
Route::get('{id}/detail-pembelian-kredit', [PembelianController::class, 'detailPembeliankredit'])->name('detail-pembelian-kredit');
Route::delete('delete-pembelian-kredit/{id}', [PembelianController::class, 'deletePembeliankredit'])->name('delete-pembelian-kredit');
Route::put('update-hutang/{id}', [PembelianController::class, 'update']);

// POS
Route::get('/transaksi-penjualan', Cart::class)->name('transaksi-penjualan');

// Piutang
Route::get('piutang', [PiutangController::class, 'index'])->name('piutang');

Route::get('hutang', [HutangController::class, 'index'])->name('hutang');

// Pendapatan
Route::get('pendapatan', [PendapatanController::class, 'index'])->name('pendapatan');
// Pendapatan Tunai
Route::get('{id}/detail-pendapatan-langsung', [PendapatanController::class, 'detailpendapatanlangsung'])->name('detail-pendapatan-langsung');
// Pendapatan Bank
Route::get('{id}/detail-pendapatan-bank', [PendapatanController::class, 'detailpendapatanbank'])->name('detail-pendapatan-bank');

// Pengeluaran 
Route::get('pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran');
// Pengeluaran Langsung
Route::get('{id}/detail-pengeluaran-langsung', [PengeluaranController::class, 'detailpengeluaranlangsung'])->name('detail-pengeluaran-langsung');
// Pengeluaran Bank
Route::get('{id}/detail-pengeluaran-bank', [PengeluaranController::class, 'detailpengeluaranbank'])->name('detail-pengeluaran-bank');
// LAST TRANSAKSI

// Migrasi
Route::get('/migrasi', [MigrasiController::class, 'index'])->name('migrasi');
Route::post('save-migrasi', [MigrasiController::class, 'savemigrasi'])->name('save-migrasi');
Route::put('updatesetuju/{id}', [MigrasiController::class, 'updatesetuju'])->name('updatesetuju');



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return 'Config cache cleared';
});
