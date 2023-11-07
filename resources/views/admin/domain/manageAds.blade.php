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
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                            <h5 class="m-0 text-white">{{$domain->domain_name}}  -  {{$domain->domain_web}}</h5>
                                <div class="d-flex text-right">
                                    @if(adminAccessRoute(config('role.domain_management.access.edit')))
                                        <a href="javascript:void(0)" data-id="{{$domain->id}}" class="btn btn-warning btn-sm btn-rounded editDomain"><i class="fa fa-edit"></i> Edit</a>
                                    @endif
                                </div>

                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <form id="formManageAds">
                                        <input type="hidden" name="domain_id" id="domain_id" value="{{$domain_id}}">
                                        <div class="row">
                                                @php
                                                    $ads_ids = [
                                                        'app_id' => 'App ID',
                                                        'open_ads_id' => 'Open Aps ID',
                                                        'banner_ads_id' => 'Banner Ads ID',
                                                        'interstitial_ads_id' => 'Interstitial Ads ID',
                                                        'native_ads_id' => 'Native Ads ID',
                                                        'rewarded_ads_id' => 'Rewarded Ads ID'
                                                    ];
                                                    $manage_ads = json_decode($domain->manage_ads, true) ?? [];
                                                @endphp

                                                @foreach($ads_ids as $key => $label)
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label>{{ $label }}</label>
                                                            <input class="form-control" type="text" name="manage_ads[{{ $key }}]" id="{{ $key }}" value="{{ $manage_ads[$key] ?? '' }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                @endforeach
                                        </div>
                                        <div class="form-group mt-2">
                                            <button type="submit" name="submit" class="btn btn-primary btn-rounded btn-block">@lang('Save changes')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.domain.components.modal')
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
                        var url = "{{ route('admin.domain.manage_ads', ['id' => ':id']) }}";
                        url = url.replace(':id', data.id);
                        window.location.href = url;
                    });
                },
            );
            setupDataTableScrollTop(tableLoadDomain,350);

            $('#formManageAds').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formManageAds")[0]);
                var  url = "{{ route('admin.domain.updateAds') }}";
                handleAjaxRequest(formData, url, null);
            });

        })

    </script>
@endpush
