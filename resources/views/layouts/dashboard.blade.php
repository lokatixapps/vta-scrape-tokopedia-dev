<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Challenge Apps - Scrape Tokopedia</title>

    <meta name="title" content="{{ config('app.name', 'Laravel') }}">
    <meta name="description" content="Challenge Apps - Scrape Tokopedia">
    <meta name="keywords" content="Challenge Apps - Scrape Tokopedia">
    <meta name="author" content="Challenge Apps - Scrape Tokopedia">

    <link rel="stylesheet" href="{{ asset('lokatix-dashboard/assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('lokatix-dashboard/assets/css/main/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('lokatix-dashboard/assets/css/shared/iconly.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="#">
                                <img src="https://vitech.asia/frontend/vta/assets/img/logo_header.svg" alt="Challenge Apps - Scrape Tokopedia" srcset="">
                            </a>
                        </div>
                        <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                </path>
                            </svg>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block">
                                <i class="bi bi-x bi-middle"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <div class="card">
                        <div class="card-body py-4 px-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl">
                                    <img src="https://vitech.asia/frontend/vta/assets/img/logo_header.svg" alt="Challenge Apps - Scrape Tokopedia">
                                </div>
                                <div class="ms-3 name">
                                    <h6 class="font-bold">Administrator</h6>
                                    <h6 class="text-muted mb-0">Admin</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="menu">
                        <li class="sidebar-item">
                            <a href="{{ route('index.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-house-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-title fs-6 text-muted">Data Master</li>
                        <li class="sidebar-item  has-sub">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-archive"></i>
                                <span>Data Produk</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item ">
                                    <a href="{{ route('product-category.index') }}"><i class="bi bi-archive"></i> Daftar Kategori Produk</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="{{ route('product.index') }}"><i class="bi bi-archive"></i> Daftar Produk</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Challenge Apps - Scrape Tokopedia</h3>
                            <p class="text-subtitle text-muted">Muhamad Lutfhan</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    @yield('content')
                </section>
            </div>
            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>Versi : Challenge Apps - Scrape Tokopedia - 1.0.0</p>
                    </div>
                    <div class="float-end">
                        <p class="color-white mb-3 font-12">&copy; {{ date('Y') }} <a href="#" class="color-white mb-3 font-12">Muhamad Lutfhan</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
<script src="{{ asset('lokatix-dashboard/assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('lokatix-dashboard/assets/js/app.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
    td.details-control {
        background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
    }

    .child-table {
        margin: 0;
        padding: 0;
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
    }

    .child-table th,
    .child-table td {
        padding: 8px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    .child-table th {
        background-color: #f2f2f2;
    }

    .child-table tr:last-child td {
        border-bottom: none;
    }
</style>
<script>
    @if(Session::has('message'))
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "newestOnTop": true,
        "positionClass": "toast-top-right",
        "timeOut": "10000",
        "extendedTimeOut": "5000",
        "showIcon": false,
        "preventDuplicates": true,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    toastr.success("{{ session('message') }}");
    @endif
  
    @if(Session::has('error'))
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "newestOnTop": true,
        "positionClass": "toast-top-right",
        "timeOut": "10000",
        "extendedTimeOut": "5000",
        "showIcon": false,
        "preventDuplicates": true,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    toastr.error("{{ session('error') }}");
    @endif
  
    @if(Session::has('info'))
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "newestOnTop": true,
        "positionClass": "toast-top-right",
        "timeOut": "10000",
        "extendedTimeOut": "5000",
        "showIcon": false,
        "preventDuplicates": true,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    toastr.info("{{ session('info') }}");
    @endif
  
    @if(Session::has('warning'))
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "newestOnTop": true,
        "positionClass": "toast-top-right",
        "timeOut": "10000",
        "extendedTimeOut": "5000",
        "showIcon": false,
        "preventDuplicates": true,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    toastr.warning("{{ session('warning') }}");
    @endif
</script>
<script>
    @if(count($errors) > 0)
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>
@yield('footer_scripts')

</html>