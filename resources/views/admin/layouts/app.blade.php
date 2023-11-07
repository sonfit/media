<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ getFile(config('location.logoIcon.path').'favicon.png')}}">
    <title>@lang($basic->site_title) | @yield('title')</title>
    <link href="{{asset('assets/admin/css/bootstrap4-toggle.min.css')}}" rel="stylesheet">
    @stack('style-lib')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/all.min.css')}}"/>
    <link href="{{asset('assets/admin/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/admin/css/style.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('assets/admin/css/magnific-popup.min.css')}}" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
    @stack('style')



</head>
<body>
<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>
<div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
    @include('admin.layouts.header')
    @include('admin.layouts.sidebar')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-2 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1 font-weight-bold">@yield('title')</h4>

                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item text-muted active" aria-current="page">@lang('Dashboard')</li>
                                <li class="breadcrumb-item text-muted" aria-current="page">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-8 align-self-center">
                    @yield('search_button')
                </div>

                <div class="col-2 text-right">
                    @yield('button')
                </div>

            </div>
        </div>
        @yield('content')
        <footer class="footer text-center text-muted">
            {{trans('Copyrights')}} © {{date('Y')}} @lang('All Rights Reserved By') @lang($basic->site_title)
        </footer>
    </div>
</div>
<script src="{{asset('assets/admin/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{asset('assets/admin/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
@stack('js-lib')

<script src="{{ asset('assets/admin/js/bootstrap4-toggle.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/app-style-switcher.js') }}"></script>
<script src="{{ asset('assets/admin/js/feather.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/notiflix-aio-2.7.0.min.js')}}"></script>
<script src="{{ asset('assets/admin/js/perfect-scrollbar.jquery.min.js')}}"></script>
<script src="{{ asset('assets/admin/js/sidebarmenu.js')}}"></script>
<script src="{{ asset('assets/admin/js/select2.min.js')}}"></script>
<script src="{{ asset('assets/admin/js/custom.js')}}"></script>
<script src="{{ asset('assets/admin/js/magnific-popup.min.js')}}"></script>

<script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@include('admin.layouts.notification')
@stack('js')
@stack('extra-script')

<script>
    let paginate = {{config('basic.paginate')}};
    let urlParams = new URLSearchParams(window.location.search);
    let page = urlParams.get('page') ? urlParams.get('page')-1 : 0; // Lấy tham số page từ URL
    let viewParam = urlParams.get('view');

    let wallpapersDeleteUrl = "{{route('admin.wallpapers.delete')}}";
    let wallpapersDeleteTagUrl = "{{route('admin.wallpapers.deleteTag')}}";
    let thumbnailWallpaperUrl = "{{ asset('storage/wallpapers/thumbnails') }}";
    let originalWallpaperUrl = "{{ asset('storage/wallpapers/originals') }}";
</script>
</body>
</html>
