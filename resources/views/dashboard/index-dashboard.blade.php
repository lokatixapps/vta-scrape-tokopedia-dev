@extends('layouts.dashboard')
@section('content')
<div class="col-12 col-lg-9">
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow-sm">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldDiscount"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7 p-0">
                            <h6 class="text-muted font-semibold">Kategori</h6>
                            <h6 class="small mb-0">{{ $productCategory->count() }} Data</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow-sm">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="iconly-boldBag"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7 p-0">
                            <h6 class="text-muted font-semibold">Produk</h6>
                            <h6 class="small mb-0">{{ $product }} Data</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Dashboard</h4>
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
        <div id="area-chart-container-priceLocation"></div>
        <hr />
        <div class="form-group">
            <label for="product_category_id">Kategori Produk<a style="color:red"> *</a></label>
            <select id="product_category_id" name="product_category_id" class="form-control" required>
                @foreach($productCategory as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <a href="#" id="filter" class="btn btn-success float-end">Cari Data</a>
        <table id="product-table" class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@section('footer_scripts')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var data = @json($productChart);
    
    var categories = data.map(function(item) {
        var date = new Date();
        date.setMonth(item.month - 1);
        var month = date.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
        return month;
    });
    
    var totalData = data.map(function(item) {
        return item.count;
    });
    
    Highcharts.chart('area-chart-container-priceLocation', {
        title: {
            text: 'Total Pengumpulan Data'
        },
        subtitle: {
            text: 'Challenge Apps - Scrape Tokopedia'
        },
        xAxis: {
            categories: categories
        },
        yAxis: {
            title: {
                text: 'Data'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            type: 'area',
            name: 'Total Data',
            data: totalData
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('#filter').click(function(e) {
            e.preventDefault();
            var categoryId = $('#product_category_id').val();

            $.ajax({
                url: '{{ route("dashboard.list.product") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: categoryId
                },
                success: function(data) {
                    $('#product-table').DataTable().clear().destroy();
                    $('#product-table').DataTable({
                        processing: true,
                        scrollX: true,
                        data: data,
                        columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            {data: 'product_category.name', name: 'product_category.name'},
                            {data: 'name', name: 'name'},
                            { 
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row) {
                                    return data;
                                }
                            },
                            {data: 'created_at', name: 'created_at'},
                            {data: 'updated_at', name: 'updated_at'},
                        ]
                    });
                }
            });
        });
    });
</script>
@stop
@endsection