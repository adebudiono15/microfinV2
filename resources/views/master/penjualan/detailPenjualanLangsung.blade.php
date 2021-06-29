<input type="text" hidden value="{{ $penjualan_langsung->id }}">
<div class="row">
    <div class="col-md-4">
     <label><b>No Invoice</b></label>
        <input class="form-control form-control-sm shadow" value="{{ $penjualan_langsung->no_struk }}" readonly>
    </div>
    <div class="col-md-4">
        <label><b>Kode Customer</b></label>
        <input class="form-control form-control-sm shadow" value="{{ $penjualan_langsung->kode_customer }}" readonly>
    </div>
    <div class="col-md-4">
        <label><b>Nama Customer</b></label>
        <input class="form-control form-control-sm shadow" value="{{ $penjualan_langsung->nama_customer }}" readonly>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-4">
     <label><b>Metode Pembayaran</b></label>
        <input class="form-control form-control-sm shadow" value="{{ $penjualan_langsung->metode_pembayaran }}" readonly>
    </div>
    <div class="col-md-4">
        <label><b>Bank</b></label>
        <input class="form-control form-control-sm shadow" value="{{ $penjualan_langsung->bank }}" readonly>
    </div>
    <div class="col-md-4">
        <label><b>Tanggal</b></label>
        <input class="form-control form-control-sm shadow" value="{{ date('d F Y', strtotime ($penjualan_langsung->created_at)) }}" readonly>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-12 mt-3">
        <div class="table-responsive">
            <table id="basic-datatables" class="display table table-hover" >
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan_langsung->lines as $e=> $item)
                    <tr>
                        <td>{{ $e+1 }}</td>
                        <td>{{ $item->namas->nama_barang }}</td>
                        <td>{{ number_format($item->harga,0) }}</td>
                        <td>{{ $item->qty }}</td>
                        <td class="text-right">{{ number_format($item->grand_total,0) }}</td>
                    </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="float-right"><small>Grand Total : </small><b>Rp. {{ number_format($penjualan_langsung->grand_total,0) }}</b></p>
    </div>
</div>