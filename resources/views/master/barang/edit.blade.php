    <div class="col-md-12">
    <input type="hidden" name="id" value="{{ $barang->id }}" id="id_data" />
        <div class="form-group">
            <label for="barang" 
            @error('barang') class="text-danger" 
            @enderror>NAMA BARANG
            @error('barang')
            | {{ $message }}
            @enderror</label>
            <input type="text" class="form-control" value="{{ $barang->nama_barang  }}"
                name="nama_barang" style="height: 28px;">
        </div>
        <div class="form-group">
            <label for="harga" 
            @error('harga') class="text-danger" 
            @enderror>HARGA BARANG
            @error('harga')
            | {{ $message }}
            @enderror</label>
            <input type="text" class="form-control" value="{{ $barang->harga  }}"
                name="harga" style="height: 28px;">
        </div>
    </div>