@extends('layouts.dashboard')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Buat Data Produk</h4>
    </div>
    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <form action="{{ route('product.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="product_category_id">Kategori Produk<a style="color:red"> *</a></label>
                <select id="product_category_id" name="product_category_id" class="form-control" required>
                    @foreach($productCategories as $productCategory)
                    <option value="{{ $productCategory->id }}">{{ $productCategory->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Nama<a style="color:red"> *</a></label>
                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <br />
            <button type="submit" class="btn btn-md btn-primary">Simpan</button>
            <a href="{{ route('product.index') }}" class="btn btn-md btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection