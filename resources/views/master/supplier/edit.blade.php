    <input type="hidden" name="id" value="{{ $supplier->id }}" id="id_data" />
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="kode_customer" @error('kode_customer') class="text-danger" @enderror>KODE SUPPLIER @error('kode_customer')
                    | {{ $message }}
                    @enderror</label>
                <input type="text" class="form-control form-control-sm shadow" value="{{ $supplier->kode_supplier }}"
                    name="kode_customer" placeholder="-" style="height: 28px;" readonly>
            </div>
        </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="nama" @error('nama') class="text-danger" @enderror>NAMA @error('nama')
                        | {{ $message }}
                        @enderror</label>
                    <input type="text" class="form-control form-control-sm shadow" value="{{ $supplier->nama }}"
                        name="nama" placeholder="-" style="height: 28px;">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="telepon" @error('telepon') class="text-danger" @enderror>TELEPON @error('telepon')
                        | {{ $message }}
                        @enderror</label>
                    <input type="number" class="form-control form-control-sm shadow" value="{{ $supplier->telepon }}"
                        name="telepon" placeholder="-" style="height: 28px;">
                </div>
            </div>
</div>

        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="email" @error('email') class="text-danger" @enderror>E-MAIL @error('email')
                        | {{ $message }}
                        @enderror</label>
                    <input type="email" class="form-control form-control-sm shadow" value="{{ $supplier->email }}"
                        name="email" placeholder="-" style="height: 28px;">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="contact_person" @error('contact_person') class="text-danger" @enderror>CONTACT PERSON @error('contact_person')
                        | {{ $message }}
                        @enderror</label>
                    <input type="text" class="form-control form-control-sm shadow" value="{{ $supplier->contact_person }}"
                        name="contact_person" placeholder="-" style="height: 28px;">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="alamat" @error('alamat') class="text-danger" @enderror>ALAMAT @error('alamat')
                        | {{ $message }}
                        @enderror</label>
                    <input type="text" class="form-control form-control-sm shadow" value="{{ $supplier->alamat }}"
                        name="alamat" placeholder="-" style="height: 28px;">
                </div>
            </div>
        </div>
    </div>