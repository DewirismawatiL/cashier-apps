@extends('main')
@section('title', 'Tambah Produk')
@section('breadcrumb', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')
<div class="container">
    <div class="card shadow-sm p-4 rounded-4">
        <form action="{{ route('products.update', $product['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control" id="price" name="price"
                            value="{{ number_format($product->price, 0, ',', '.') }}" placeholder="contoh: 20.000">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Foto Produk <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="image" name="image" value="{{ $product->image }}">
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mt-3 w-25 rounded-5">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
