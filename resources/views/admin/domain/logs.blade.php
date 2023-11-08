@extends('admin.layouts.app')
@section('title',trans($title))
@section('content')
    <div class="container-fluid">
        <div class="row mt-sm-4 justify-content-center">
            <div class="col-12 col-md-2 col-lg-2">
                @include('admin.domain.components.sidebar', ['settings' => config('generalsettings.domain'), 'suffix' => '','domain_id'=>$domain_id])
                @include('admin.domain.components.loadCategories', ['settings' => config('generalsettings.domain'), 'suffix' => '','domain_id'=>$domain_id])
            </div>
            <div class="col-12 col-md-10 col-lg-10">
                <div class="container-fluid" id="container-wrapper">
                    <div class="card mb-4 card-primary shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered" id="tableLogs" style="width: 100%">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">@lang('Ip Address')</th>
                                        <th scope="col">@lang('Device Name')</th>
                                        <th scope="col">@lang('Platform Name')</th>
                                        <th scope="col">@lang('Country')</th>
                                        <th scope="col">@lang('Count')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        $(document).ready(function () {
            "use strict";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const url = window.location.href;
            const parts = url.split('/');
            const urlId = parts[parts.length - 2];
            let logsIndexUrl = `{{ route('admin.domain.getDomainLogs',['id'=>':id']) }}`;
            logsIndexUrl = logsIndexUrl.replace(':id', urlId);


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
                        var url = "{{ route('admin.domain.logs', ['id' => ':id']) }}";
                        url = url.replace(':id', data.id);
                        window.location.href = url;
                    });
                },
            );
            const tableLogs = setupDataTable(
                '#tableLogs',
                logsIndexUrl,
                [
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'device_name', name: 'device_name'},
                    {data: 'platform_name', name: 'platform_name'},
                    {data: 'country', name: 'country'},
                    {data: 'count', name: 'count'},
                ],
                [[ 4, 'asc' ]],
                null,
            );
        })

    </script>
@endpush
