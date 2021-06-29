@extends('layouts.master')

@section('title', 'Barang')

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">@yield('title')</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row mt--2 justify-content-center">
                <div class="col-md-12">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Nama</th>
                                            <th>Kategori</th>
                                            <th style="width: 200px" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barang as $e => $item)
                                            <tr>
                                                <td>{{ $e + 1 }}</td>
                                                <td>{{ $item->nama_barang }}</td>
                                                <td>{{ $item->kategori->nama }}</td>
                                                <td class="text-center" style="width: 200px">
                                                    <a href="#" data-id="{{ $item->id }}"
                                                        class="btn btn-sm btn-info btn-shadow mr-2 mt-2 mb-2 btn-edit">
                                                        <i class="far fa-edit"></i>
                                                        <span class="align-middle">EDIT</span>
                                                    </a>

                                                    <a href="#" data-id="{{ $item->id }}"
                                                        class="btn btn-sm btn-danger btn-shadow mt-2 mb-2 swal-confirm">
                                                        <form action="{{ route('delete-barang', $item->id) }}"
                                                            id="delete{{ $item->id }}" method="POST">
                                                            @csrf
                                                            
                                                            @method('delete')
                                                            <i data-id="{{ $item->id }}" class="fas fa-trash-alt"></i>
                                                            <span data-id="{{ $item->id }}" class="align-middle">HAPUS
                                                        </form>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="custom-template">
        <div class="custom-toggle" href="#insert" data-toggle="modal">
            <i class="fas fa-plus-circle"></i>
        </div>
</div>

    {{-- Insert --}}
    <div id="insert" class="modal fade" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white">TAMBAH DATA BARANG</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <form class="form form-vertical" method="post" enctype="multipart/form-data"
                    action="{{ route('save-barang') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="nama_barang" @error('nama_barang') class="text-danger" @enderror>NAMA BARANG
                                        @error('nama_barang')
                                            | {{ $message }}
                                        @enderror</label>
                                    <input type="text" class="form-control form-control-sm shadow"
                                        value="{{ old('nama_barang') }}" name="nama" placeholder="-"
                                        style="height: 28px;">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="satuan" @error('satuan') class="text-danger" @enderror>SATUAN
                                        @error('satuan')
                                            | {{ $message }}
                                        @enderror</label>
                                    <select id="satuan" name="satuan" class="js-states form-control" style="width: 100%">
                                        <option value=""></option>
                                        @foreach ($satuan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="kategori" @error('kategori') class="text-danger" @enderror>KATEGORI
                                        @error('kategori')
                                            | {{ $message }}
                                        @enderror</label>
                                    <select id="kategori" name="kategori" class="js-states form-control" style="width: 100%">
                                        <option value=""></option>
                                        @foreach ($kategori as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="supplier" @error('supplier') class="text-danger" @enderror>SUPPLIER
                                        @error('supplier')
                                            | {{ $message }}
                                        @enderror</label>
                                    <select id="supplier" name="supplier" class="js-states form-control" style="width: 100%">
                                        <option value=""></option>
                                        @foreach ($supplier as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="">HARGA BELI</label>
                                  <input type="text" id="hargabeli" name="harga_beli" class="form-control form-control-sm shadow" placeholder="-" >
                                  <small style="font-size:10px;" class="text-danger">Masukan harga /1 ml jika kategori Refil Parfum</small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">HARGA JUAL</label>
                                    <input type="text" id="hargajual" name="harga_jual" class="form-control form-control-sm shadow" placeholder="-" >
                                    <small style="font-size:10px;" class="text-danger">Masukan harga /1 ml jika kategori Refil Parfum</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                  <label for="">Stok</label>
                                  <input type="text" id="stock" name="stock" class="form-control form-control-sm shadow" placeholder="-" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-info btn-shadow">Simpan</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- last Insert --}}

    {{-- Edit --}}
    <div id="edit" class="modal fade" style="display: none;" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white">EDIT DATA BARANG</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <form class="form form-vertical" method="post" id="form-edit" enctype="multipart/form-data"
                    action="{{ route('save-barang') }}">
                    @csrf
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-info btn-shadow btn-update">Update</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- last Edit --}}
@endsection

@push('js')
    <script type="text/javascript">
        var hargabeli = document.getElementById('hargabeli');
        hargabeli.addEventListener('keyup', function(e) {

            hargabeli.value = formatRupiah(this.value, 'Rp. ');
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }

    </script>
    <script type="text/javascript">
        var hargajual = document.getElementById('hargajual');
        hargajual.addEventListener('keyup', function(e) {

            hargajual.value = formatRupiah(this.value, 'Rp. ');
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }

    </script>
    <script type="text/javascript">
        var stock = document.getElementById('stock');
        stock.addEventListener('keyup', function(e) {

            stock.value = formatRupiah(this.value, 'Rp. ');
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }

    </script>
    <script>
        $("#supplier").select2({
            placeholder: "Pilih Supplier",
            allowClear: true
        });
        $("#satuan").select2({
            placeholder: "Pilih Satuan",
            allowClear: true
        });
        $("#kategori").select2({
            placeholder: "Pilih Kategori",
            allowClear: true
        });

    </script>
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({});
        });

    </script>
    <script>
        $('.btn-edit').on('click', function() {
            // console.log($(this).data('id'))
            let id = $(this).data('id')
            $.ajax({
                url: `/${id}/edit-barang`,
                method: "GET",
                success: function(data) {
                    // console.log(data)
                    $('#edit').find('.modal-body').html(data)
                    $('#edit').modal('show')
                },
                error: function(error) {
                    console.log(error)
                }
            })
        })

        $('.btn-update').on('click', function() {
            // console.log($(this).data('id'))
            let id = $('#form-edit').find('#id_data').val()
            let formData = $('#form-edit').serialize()
            console.log(formData)
            $.ajax({
                url: `/barang/update/${id}`,
                method: "PATCH",
                data: formData,
                success: function(data) {
                    // console.log(data)
                    $('#edit').modal('hide')
                    window.location.assign('/barang')
                },
                error: function(err) {
                    console.log(err.responseJSON)
                    let err_log = err.responseJSON.errors;
                    if (err.status == 422) {
                        if (typeof(err_log.satuan) !== 'undefined') {
                            $('#edit').find('[name="divisi"]').prev().html(
                                '<span style="color:red">Divisi | ' + err_log.divisi[0] + '</span>')
                        } else {
                            $('#edit').find('[name="divisi"]').prev().html('<span>Divisi</span>')
                        }
                    }
                }
            })
        })

        @if ($errors->any()) {
            $('#insert').modal('show')
            }
        @endif

        $(".swal-confirm").click(function(e) {
            id = e.target.dataset.id;
            Swal.fire({
                    title: 'YAKIN MAU HAPUS DATA?',
                    text: "Data Akan Dihapus Permanen",
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'YA, HAPUS!'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $(`#delete${id}`).submit();
                    } else {}
                });
        });

    </script>

@endpush
