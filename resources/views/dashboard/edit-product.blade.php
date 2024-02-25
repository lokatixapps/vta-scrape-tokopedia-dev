@extends('layouts.dashboard')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Data Produk</h4>
    </div>
    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <form action="{{ route('product.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="categoryProductName">Kategori Produk<a style="color:red"> *</a></label>
                <input type="text" id="categoryProductName" class="form-control @error('categoryProductName') is-invalid @enderror" name="categoryProductName" value="{{ old('categoryProductName', $product->productCategory->name) }}" maxlength="100" disabled>
                @error('categoryProductName')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="name">Nama Produk<a style="color:red"> *</a></label>
                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name) }}" maxlength="100" required>
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="data">Data Produk<a style="color:red"> *</a></label>
                <textarea type="text" id="data" class="form-control @error('data') is-invalid @enderror" name="data" value="{{ old('data') }}" rows="10" cols="50" required>{{ $product->data }}</textarea>
                @error('data')
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