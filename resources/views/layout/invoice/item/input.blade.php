<div class="p-3">
    @include('form.header.start')
    <input type="hidden" name="invoice" value="{{ old('invoice', $data->invoice) }}">
    <div class="mb-3">
        <label class="form-label">Item<span class="text-danger">*</span></label>
        <input type="text" name="item" class="form-control @error('item') is-invalid @enderror" value="{{ old('item', $data->item) }}" placeholder="Masukan nama item">
        @error('item')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Description<span class="text-danger">*</span></label>
        <input type="text" name="desc" class="form-control @error('desc') is-invalid @enderror" value="{{ old('desc', $data->desc) }}" placeholder="Masukan deskripsi">
        @error('desc')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Price<span class="text-danger">*</span></label>
        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $data->price) }}" placeholder="Masukan harga">
        @error('price')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Qty<span class="text-danger">*</span></label>
        <input type="number" name="qty" class="form-control w-50 @error('qty') is-invalid @enderror" value="{{ old('qty', $data->qty) }}" placeholder="Masukan kuantitas">
        @error('qty')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Disc *%<span class="text-danger">*</span></label>
        <input type="number" name="disc" class="form-control w-50 @error('disc') is-invalid @enderror" value="{{ old('disc', $data->disc) }}" placeholder="Masukan diskon">
        @error('disc')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <button type="reset" class="btn btn-outline-primary w-100 mb-2">Reset</button>
    <button type="submit" class="btn btn-primary w-100">Save</button>
    @include('form.header.end')
</div>