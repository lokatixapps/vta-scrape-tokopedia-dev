@extends('layouts.dashboard')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Detail Produk - "{{ $product->name }}" (Kategori : {{ $product->productCategory->name }})</h4>
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
        <div class="container">
            <div class="row">
                <div class="col">
                    <div id="pie-chart-container-location"></div>
                </div>
                <div class="col">
                    <div id="pie-chart-container-price"></div>
                </div>
            </div>
        </div>
        <div id="area-chart-container-priceLocation"></div>
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Lokasi</th>
                    <th>URL</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@section('footer_scripts')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    var data = @json($formattedLocationGroupData);
    
    var chartData = [];
    data.forEach(function(item) {
        chartData.push([item.location, item.total_items]);
    });

    Highcharts.chart('pie-chart-container-location', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Jumlah Produk - Lokasi'
        },
        series: [{
            name: 'Lokasi',
            data: chartData
        }]
    });
</script>
<script>
    var data = @json($formattedPriceGroupData);
    
    var chartData = [];
    data.forEach(function(item) {
        chartData.push([item.price_group, item.total_items]);
    });

    Highcharts.chart('pie-chart-container-price', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Kategori Produk - Harga'
        },
        series: [{
            name: 'Harga',
            data: chartData
        }]
    });
</script>
<script type="text/javascript">
    var data = @json($formattedPriceLocationGroupData);

    var categories = Object.keys(data);
    var seriesData = [];

    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            var sum = data[key].price_data.reduce(function(a, b) { 
                return a + b; 
            }, 0);
            seriesData.push({
                name: key,
                y: sum
            });
        }
    }

    Highcharts.chart('area-chart-container-priceLocation', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Total Harga - Lokasi'
        },
        xAxis: {
            categories: categories
        },
        yAxis: {
            title: {
                text: 'Total Harga'
            },
            labels: {
                formatter: function () {
                    return this.value;
                }
            }
        },
        series: [
            {
                name: 'Total Harga',
                data: seriesData
            }
        ]
    });
</script>
<script type="text/javascript">
    $(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: "{{ route('detail.product', ['id' => $product->id]) }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'title', name: 'title', orderable: false, searchable: true},
                {data: 'price', name: 'price', orderable: false, searchable: true},
                {data: 'location', name: 'location', orderable: false, searchable: true},
                {data: 'url', name: 'url', orderable: false, searchable: true},
            ]
        });
    });
</script>
@stop
@endsection