<?php

namespace App\Http\Livewire;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\Penjualan;
use App\Models\PenjualanKredit;
use App\Models\PenjualanKreditLine;
use App\Models\PenjualanLine;
use Carbon\Carbon;
use Livewire\Component;
use Session;
use Livewire\WithPagination;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class Cart extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $tax = "0%";
    public $search;

    // Pembayaran
    public $metodePembayaran;
    public $Bank;
    public $kategoriPembayaran;
    public $payment = '';
    public $grand_total = 0;

    // Edit harga
    public $qtybarang;
    public $barang;
    public $status;

    // Harga
    public $hargajualrefil;
    public $satuml;
    public $duaratuslimapuluhml;
    public $limaratusml;
    public $satuliterml;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public $selecttedItem;
    public $selecttedItemLangsung;
    public $langsungbeli;
    public $tesprint = 'hidden';
    public $viewbank = "hidden";
    public $viewlangsung = "hidden";


    protected $listeners = [
        'refreshParent' => '$refresh'
    ];

    public $harga = '';
    public $customerPos;


    public function render()
    {
        $products = Barang::where('nama_barang', 'like', '%' . $this->search . '%')->orderBy('created_at', 'ASC')->paginate('9');

        $condition = new \Darryldecode\Cart\CartCondition([
            'name' => 'pajak',
            'type' => 'tax',
            'target' => 'total',
            'value' => $this->tax,
            'order' => 1
        ]);
        \Cart::session(Auth()->id())->condition($condition);
        $items = \Cart::session(Auth()->id())->getContent()->sortBy(function ($cart) {
            return $cart->attributes->get('added_at');
        });

        if (\Cart::isEmpty()) {
            $cartData = [];
        } else {
            foreach ($items as $item) {
                $cart[] = [
                    'rowId' => $item->id,
                    'name' => $item->name,
                    'qty' => $item->quantity,
                    'satuan' => $item->satuan,
                    'pricesingle' => $item->price,
                    'price' => $item->getPriceSum(),
                ];
            }

            $cartData = collect($cart);
        }

        $sub_total = \Cart::session(Auth()->id())->getSubTotal();
        $total = \Cart::session(Auth()->id())->getTotal();
        $newCondition = \Cart::session(Auth()->id())->getCondition('pajak');
        $pajak = $newCondition->getCalculatedValue($sub_total);

        $summary = [
            'sub_total' => $sub_total,
            'pajak' => $pajak,
            'total' => $total
        ];

        // kode
        $firstInvoiceID = Penjualan::whereDay('created_at', date('d'))->count('id');
        $secondInvoiceID = $firstInvoiceID + 1;
        $nomor = sprintf("%03d", $secondInvoiceID);
        $tanggal = date('Ymd');
        $kode = "DC/INV/PNJ-T/$tanggal/$nomor";
        // 

        // customer
        $customer = Customer::get();

        // Metode Pembayaran
        $metode = [
            ["metode" => "Cash"],
            ["metode" => "Debit"],
            ["metode" => "Transfer"],
        ];
        // List Bank
        $listbank = [
            ["nama" => "BRI"],
            ["nama" => "BNI"],
            ["nama" => "BCA"],
            ["nama" => "Mandiri"],
            ["nama" => "Panin"],
            ["nama" => "Lainnya"],
        ];

        if ($this->metodePembayaran == ("Transfer" && "Debit")) {
            $this->viewbank = "show";
        }
        if ($this->metodePembayaran == "Cash") {
            $this->viewbank = "hidden";
        }

        if ($this->kategoriPembayaran == "Langsung") {
            $this->viewlangsung = "show";
        }
        if ($this->kategoriPembayaran == "Kredit") {
            $this->viewlangsung = "hidden";
        }


        return view('livewire.cart', [
            'products' => $products,
            'carts' => $cartData,
            'summary' => $summary,
            'kode' => $kode,
            'customer' => $customer,
            'metode' => $metode,
            'listbank' => $listbank
        ]);
    }


    public function selecttedItemLangsung($itemId, $action)
    {
        $this->selecttedItemLangsung = $itemId;
        $barang = Barang::find($itemId);
        $this->status = $barang->status;
        if ($action == 'langsungbeli') {
            $this->status = $barang->status;
            $this->barang = $barang->nama_barang;
            $this->satuml = $barang->hj1;
            $this->duaratuslimapuluhml = $barang->hj250;
            $this->limaratusml = $barang->hj500;
            $this->satuliterml = $barang->hj1l;
            if ($barang->status == 1) {
                $this->dispatchBrowserEvent('openModalQtyrefil');
            } else {
                $this->dispatchBrowserEvent('openModalQty');
            }
            
        }
    }

    public function submitEditHargaLangsung($itemId, $action)
    {
        $id = $this->selecttedItemLangsung = $itemId;
        $barang = Barang::find($itemId);
        $harga_jual = $barang->harga_jual;
        $status = $barang->status;
        $harga_jualref = $this->hargajualrefil;
        $qtybarang_sem = $this->qtybarang;
        $qtybarang = str_replace([".", "Rp", " "], '', $qtybarang_sem);
        $cart = \Cart::session(Auth()->id())->getContent();
        $cekItemId = $cart->whereIn('id', $id);

        if ($action == 'submitButtonHargaLangsung') {
            if ($status === 1) {
                if ($cekItemId->isNotEmpty()) {
                    \Cart::session(Auth()->id())->update($rowId, [
                        'quantity' => [
                            'relative' => true,
                            'value' => $qtybarang
                        ]
                    ]);
                    $this->dispatchBrowserEvent('swal', [
                     // 'title' => 'ðŸ˜«',
                        'text' => 'Sukses Masuk Keranjang',
                        'timer' => 1500,
                        'icon' => false,
                        'showConfirmButton' => false,
                        'position' => 'center'
                    ]);
                } else {
                    $product = Barang::findOrFail($id);
                    \Cart::session(Auth()->id())->add([
                        'id' => "Cart" . $product->id,
                        'name' => $product->nama_barang,
                        'price' => $harga_jualref,
                        'quantity' => $qtybarang,
                        'attributes' => [
                            'satuan' => $product->satuan_id,
                            'harga' => $harga_jualref,
                            'added_at' => Carbon::now()
                        ],
                    ]);
                    $this->dispatchBrowserEvent('swal', [
                        // 'title' => 'ðŸ˜«',
                        'text' => 'Sukses Masuk Keranjang',
                        'timer' => 1500,
                        'icon' => false,
                        'showConfirmButton' => false,
                        'position' => 'center'
                    ]);
                }
            }else{
                if ($cekItemId->isNotEmpty()) {
                    \Cart::session(Auth()->id())->update($rowId, [
                        'quantity' => [
                            'relative' => true,
                            'value' => $qtybarang
                        ]
                    ]);
                    $this->dispatchBrowserEvent('swal', [
                   // 'title' => 'ðŸ˜«',
                        'text' => 'Sukses Masuk Keranjang',
                        'timer' => 1500,
                        'icon' => false,
                        'showConfirmButton' => false,
                        'position' => 'center'
                    ]);
                } else {
                    $product = Barang::findOrFail($id);
                    \Cart::session(Auth()->id())->add([
                        'id' => "Cart" . $product->id,
                        'name' => $product->nama_barang,
                        'price' => $harga_jual,
                        'quantity' => $qtybarang,
                        'attributes' => [
                            'satuan' => $product->satuan_id,
                            'harga' => $harga_jual,
                            'added_at' => Carbon::now()
                        ],
                    ]);
                    $this->dispatchBrowserEvent('swal', [
                      // 'title' => 'ðŸ˜«',
                        'text' => 'Sukses Masuk Keranjang',
                        'timer' => 1500,
                        'icon' => false,
                        'showConfirmButton' => false,
                        'position' => 'center'
                    ]);
                }
            }

            if ($status == 1) {
                $this->dispatchBrowserEvent('closeModalQtyrefil');
            } else {
                $this->dispatchBrowserEvent('closeModalQty');
            }
            $this->hapusBayar();
        }
    }

    public function cancelEditLangsung($itemId, $action)
    {
        $barang = Barang::find($itemId);
        $status = $barang->status;

        if ($action == 'cancelButtonHargaLangsung') {
            
            if ($status == 1) {
                $this->dispatchBrowserEvent('swal', [
                  // 'title' => 'ðŸ˜«',
                    'text' => 'Ko Ga Jadi?',
                    'timer' => 1500,
                    'icon' => false,
                    'showConfirmButton' => false,
                    'position' => 'center'
                ]);
                $this->hapusBayar();
                $this->dispatchBrowserEvent('closeModalQtyrefil');
            } else {
                $this->dispatchBrowserEvent('swal', [
                 // 'title' => 'ðŸ˜«',
                    'text' => 'Ko Ga Jadi?',
                    'timer' => 1500,
                    'icon' => false,
                    'showConfirmButton' => false,
                    'position' => 'center'
                ]);
                $this->hapusBayar();
                $this->dispatchBrowserEvent('closeModalQty');
            }
           
        }
    }

    public function selecttedItem($itemId, $action)
    {
        $this->selecttedItem = $itemId;
        $barang = Barang::find($itemId);
        if ($action == 'editharga') {
            $this->barang = $barang->nama_barang;
            $this->dispatchBrowserEvent('openModal');
        }
    }

    public function submitEditHarga($itemId, $action)
    {
        $id = $this->selecttedItem = $itemId;
        $edit_harga_sem = $this->harga;
        $qtybarang_sem = $this->qtybarang;
        $editharga = str_replace([".", "Rp", " "], '', $edit_harga_sem);
        $qtybarang = str_replace([".", "Rp", " "], '', $qtybarang_sem);
        $cart = \Cart::session(Auth()->id())->getContent();
        $cekItemId = $cart->whereIn('id', $id);

        if ($action == 'submitButtonHarga') {

            if (!$editharga) {
                $this->dispatchBrowserEvent('swal', [
                    // 'title' => 'ðŸ˜«',
                    'text' => 'Masukin Harganya Dulu...',
                    'timer' => 2000,
                    'icon' => false,
                    'showConfirmButton' => false,
                    'position' => 'center'
                ]);
            } else {
                if ($cekItemId->isNotEmpty()) {
                    \Cart::session(Auth()->id())->update($rowId, [
                        'quantity' => [
                            'relative' => true,
                            'value' => $qtybarang
                        ]
                    ]);
                    $this->dispatchBrowserEvent('swal', [
                        // 'title' => 'ðŸ˜Ž',
                        'text' => 'Sukses Masuk Keranjang',
                        'timer' => 1500,
                        'icon' => false,
                        'showConfirmButton' => false,
                        'position' => 'center'
                    ]);
                } else {
                    $product = Barang::findOrFail($id);
                    \Cart::session(Auth()->id())->add([
                        'id' => "Cart" . $product->id,
                        'name' => $product->nama_barang,
                        'price' => $editharga,
                        'quantity' => $qtybarang,
                        'attributes' => [
                            'satuan' => $product->satuan_id,
                            'harga' => $editharga,
                            'added_at' => Carbon::now()
                        ],
                    ]);
                    $this->dispatchBrowserEvent('swal', [
                        // 'title' => 'ðŸ˜Ž',
                        'text' => 'Sukses Masuk Keranjang',
                        'timer' => 1500,
                        'icon' => false,
                        'showConfirmButton' => false,
                        'position' => 'center'
                    ]);
                }
            }
            $this->hapusBayar();
            $this->dispatchBrowserEvent('closeModal');
        }
    }

    public function increaseItem($rowId)
    {
        $idProduct = substr($rowId, 4, 5);
        $product = Barang::find($idProduct);
        $cart = \Cart::session(Auth()->id())->getContent();
        $checkItem = $cart->whereIn('id', $rowId);
        if ($product->stock == $checkItem[$rowId]->quantity) {
            $this->dispatchBrowserEvent('swal', [
                // 'title' => 'ðŸ˜­',
                'text' => 'Stok Lagi Tinggal Segitu',
                'timer' => 1500,
                'icon' => 'warning',
                'showConfirmButton' => false,
                'position' => 'center'
            ]);
        } else {
            \Cart::session(Auth()->id())->update($rowId, [
                'quantity' => [
                    'relative' => true,
                    'value' => 1
                ]
            ]);
        }
    }

    public function decreaceItem($rowId)
    {
        $idProduct = substr($rowId, 4, 5);
        $product = Barang::find($idProduct);
        $cart = \Cart::session(Auth()->id())->getContent();
        $checkItem = $cart->whereIn('id', $rowId);

        if ($checkItem[$rowId]->quantity == 1) {
            $this->removeItem($rowId);
        } else {
            \Cart::session(Auth()->id())->update($rowId, [
                'quantity' => [
                    'relative' => true,
                    'value' => -1
                ]
            ]);
        }
    }

    public function removeItem($rowId)
    {
        \Cart::session(Auth()->id())->remove($rowId);
    }

    public function handleSubmit()
    {
        $firstInvoiceID = Penjualan::whereDay('created_at', date('d'))->count('id');
        $secondInvoiceID = $firstInvoiceID + 1;
        $nomor = sprintf("%03d", $secondInvoiceID);
        $tanggal = date('Ymd');
        $kode = "MP1/INV/PNJ-T/$tanggal/$nomor";
        // last inv
        $cartTotal = \Cart::session(Auth()->id())->getTotal();
        $bayar = $this->payment;
        $kembalian = (int) $bayar - (int) $cartTotal;
        // if ($kembalian >= 0) {
                    
        // }
            DB::beginTransaction();
            try {
                $allCart = \Cart::session(Auth()->id())->getContent();
                $filterCart = $allCart->map(function ($item) {
                    return [
                        'id' => substr($item->id, 4, 5),
                        'quantity' => $item->quantity,
                        'satuan' => $item->attributes['satuan'],
                        'harga' => $item->attributes['harga']
                    ];
                });

                foreach ($filterCart as $cart) {
                    $product = Barang::find($cart['id']);
                    if ($product->stock === 0) {
                    }
                    $product->decrement('stock', $cart['quantity']);
                }

                if (!$this->metodePembayaran) {
                    $this->metodePembayaran = "Cash";
                }
                
                // Kasir
                $user = Auth::user();

                // CUstomer
                $customer = Customer::find($this->customerPos);
        
                if ($this->kategoriPembayaran == "Langsung") {
                    $header = Penjualan::insertGetId([
                        'no_struk' => $kode,
                        'kasir' => $user->name,
                        'nama' => '',
                        'nama_customer' => $customer->nama,
                        'alamat' => $customer->alamat,
                        'telepon' => $customer->telepon,
                        'customer_id' => $customer->id,
                        'metode_pembayaran' => $this->metodePembayaran,
                        'bank' => $this->Bank,
                        'grand_total' => $cartTotal,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
    
                    foreach ($filterCart as $cart) {
                        PenjualanLine::create([
                            'penjualan' => $header,
                            'nama' => $cart['id'],
                            'harga' => $cart['harga'],
                            'qty' => $cart['quantity'],
                            'satuan_id' => $cart['satuan'],
                            'grand_total' => $cart['quantity'] * $cart['harga'],
                        ]);
                    }
                } else{
                    $header = PenjualanKredit::insertGetId([
                        'no_struk' => $kode,
                        'kasir' => $user->name,
                        'nama' => '',
                        'nama_customer' => $customer->nama,
                        'alamat' => $customer->alamat,
                        'telepon' => $customer->telepon,
                        'customer_id' => $customer->id,
                        'status' => 1,
                        'sisa' => $cartTotal,
                        'grand_total' => $cartTotal,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
    
                    foreach ($filterCart as $cart) {
                        PenjualanKreditLine::create([
                            'penjualan_kredit' => $header,
                            'nama' => $cart['id'],
                            'harga' => $cart['harga'],
                            'qty' => $cart['quantity'],
                            'satuan_id' => $cart['satuan'],
                            'sisa' => $cart['quantity'] * $cart['harga'],
                            'grand_total' => $cart['quantity'] * $cart['harga'],
                        ]);
                    }
                }
                
                \Cart::clear();
                \Cart::session(Auth()->id())->clear();
                $this->hapusBayar();

                $this->dispatchBrowserEvent('swal', [
                    // 'title' => 'ðŸ˜˜',
                    'text' => 'Yeay... Berhasil Melakukan Transaksi...',
                    'timer' => 2000,
                    'icon' => false,
                    'showConfirmButton' => false,
                    'position' => 'center'
                ]);

                DB::commit();
            } catch (\Throwable $th) {
                dd($th);
                DB::rollback();
            }
    }

    private function hapusBayar()
    {
        $this->payment = null;
        $this->paymenText = null;
        $this->kembalianText = null;
        $this->editHarga = null;
        $this->qtybarang = null;
        $this->harga = null;
        $this->customerPos = null;
        $this->metodePembayaran = null;
        $this->Bank = null;
        $this->hargajualrefil = null;
        $this->kategoriPembayaran = null;
        $this->viewlangsung = null;
        $this->viewbank = "hidden";
    }
}
