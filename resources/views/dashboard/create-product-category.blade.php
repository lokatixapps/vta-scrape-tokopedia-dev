@extends('layouts.dashboard')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Buat Data Kategori Produk</h4>
    </div>
    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <form action="{{ route('product-category.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Kategori Produk<a style="color:red"> *</a></label>
                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" maxlength="100" required>
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Deskripsi Kategori Produk<a style="color:red"> *</a></label>
                <textarea type="text" id="description" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" rows="10" cols="50" maxlength="1000" required></textarea>
                @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <br />
            <button type="submit" class="btn btn-md btn-primary">Simpan</button>
            <a href="{{ route('product-category.index') }}" class="btn btn-md btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection