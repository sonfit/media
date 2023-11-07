@extends('admin.layouts.app')
@section('title',trans($title))
@push('style-lib')
    <link href="{{asset('assets/admin/css/dropzone.min.css')}}" rel="stylesheet">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row mt-sm-4 justify-content-center">
            <div class="col-12 col-md-2 col-lg-2">
                @include('admin.domain.components.sidebar', ['settings' => config('generalsettings.domain'), 'suffix' => '','domain_id'=>$domain_id])
                @include('admin.domain.components.loadCategories', ['settings' => config('generalsettings.domain'), 'suffix' => '','domain_id'=>$domain_id])
            </div>
            <div class="col-12 col-md-10 col-lg-10">

                @include('partials.wallpapers')
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
    <script src="{{ asset('assets/admin/js/wallpaper.js')}}"></script>

    <script>
        const url = window.location.href;
        const parts = url.split('/');
        let urlId = parts[parts.length - 2];
        let wallpapersIndexUrl = `{{ route('admin.domain.getDomainWallpapers',['id'=>':id']) }}`;
        wallpapersIndexUrl = wallpapersIndexUrl.replace(':id', urlId);

        $(document).ready(function () {
            "use strict";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const tableLoadDomain = setupDataTable(
                '#tableLoadDomain',
                "{{ route('admin.domain.getIndex')}}",
                [
                    {data: 'id', name: 'id', visible: false},
                    {data: 'domain_web', name: 'domain_web'},
                ],
                [[ 0, 'asc' ]],
                null,
                null,
                function (row, data) {
                    if (data.id == urlId) {
                        $(row).addClass('selected');
                    }
                    $(row).on('click', function () {
                        var url = "{{ route('admin.domain.wallpapers', ['id' => ':id']) }}";
                        url = url.replace(':id', data.id);
                        window.location.href = url;
                    });
                },
            );
            setupDataTableScrollTop(tableLoadDomain,350);


        })
    </script>


@endpush
