@extends('layouts.dashboard')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Daftar Produk</h4>
    </div>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <a href="{{ route('product.create') }}" class="btn btn-md btn-success mb-3 float-right">Tambah Data</a>
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama</th>
                    <th>Data</th>
                    <th>Aksi</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@section('footer_scripts')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: "{{url('list/product')}}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'product_category.name', name: 'product_category.name', orderable: false, searchable: true},
                {data: 'name', name: 'name', orderable: false, searchable: true},
                {data: 'data', name: 'data', orderable: false, searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: true},
                {data: 'created_at', name: 'created_at', orderable: false, searchable: true},
                {data: 'updated_at', name: 'updated_at', orderable: false, searchable: true},
            ]
        });
    });
</script>
@stop
@endsection