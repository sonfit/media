@extends('admin.layouts.app')
@section('title',trans($title))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="p-4 border shadow-sm rounded">
                        <div class="row">

                            <div class="col-md-4 border-right">
                                <ul class="list-style-none  border-bottom">
                                    <li class="my-2 border-bottom pb-3">
                                        <span class="font-weight-medium text-dark">
                                            <i class="icon-info mr-2 text--site"></i> Information:
                                            <span id="editSpan" class="badge badge-pill badge-warning font-16 ml-auto"><i class="fa fa-edit"></i> Edit</span>
                                            {!! $redirect->is_devices == 1 ? ' <span class="badge badge-pill badge-success float-right font-16 ml-auto">Devices</span> ' : ' <span class="badge badge-pill badge-danger float-right font-16 ml-auto">Country</span> '!!}
                                            {!! $redirect->is_webview == 1 ? ' <span class="badge badge-pill badge-primary float-right font-16 ml-auto">Webview</span> ' : ''!!}
                                            {!! $redirect->is_ads == 1 ? '<span class="badge badge-pill badge-warning float-right font-16">Ads</span>' : ''!!}

                                        </span>

                                    </li>
                                    <li class="my-3">
                                        <span>
                                            <i class="icon-check mr-2 text--site"></i> {{trans('Name')}} :
                                            <span
                                                class="font-weight-medium text-dark">{{$redirect->redirect_name}}</span>
                                        </span>
                                    </li>

                                    <li class="my-3">
                                        <span>
                                            <i class="icon-check mr-2 text--site"></i> {{trans('Url')}} :
                                            <span class="font-medium">#{{$redirect->redirect_url}}</span>
                                        </span>
                                    </li>
                                    <li class="my-3">
                                        <span>
                                            <i class="icon-check mr-2 text--site"></i> {{trans('Domain')}} :
                                            @foreach($redirect->getDomains as $domain)
                                                <span
                                                    class="badge badge-pill badge-secondary m-1 font-16">{{$domain->domain_web}}</span>
                                            @endforeach
                                        </span>
                                    </li>
                                    <li class="my-3">
                                        <span>
                                            <i class="icon-check mr-2 text--site"></i> {{trans('Url Block')}} :
                                            <span
                                                class="font-weight-medium">{!! $redirect->redirect_url_block ?? "N/a" !!}</span>
                                        </span>
                                    </li>
                                    <li class="my-3">
                                        <span>
                                            <i class="icon-check mr-2 text--site"></i> {{trans('Exp Date')}} :
                                            <span class="font-medium">{{$redirect->exp_date_at}}</span>
                                        </span>
                                    </li>
                                </ul>

                                <ul class="list-style-none mt-4">
                                    <li class="my-2 border-bottom pb-3">
                                        <span class="font-weight-medium text-dark">
                                            <i class="fa fa-globe mr-2 text-secondary"></i> Ads:
                                        </span>
                                    </li>

                                    @php
                                        $ads_ids = [
                                            'app_id' => 'App ID',
                                            'open_ads_id' => 'Open Aps ID',
                                            'banner_ads_id' => 'Banner Ads ID',
                                            'interstitial_ads_id' => 'Interstitial Ads ID',
                                            'native_ads_id' => 'Native Ads ID',
                                            'rewarded_ads_id' => 'Rewarded Ads ID'
                                        ];
                                        $manage_ads = json_decode($redirect->manage_ads, true) ?? [];
                                    @endphp

                                    @foreach($ads_ids as $key => $label)
                                        <li class="my-3 ">
                                            <span><i class="icon-check mr-2 text-secondary"></i> {{$label}} : <span
                                                    class="font-weight-medium">{{$manage_ads[$key] ?? 'N/A'}}</span>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-4 border-right">
                                <ul class="list-style-none border-bottom">
                                    <li class="my-2 border-bottom pb-3">
                                        <span class="font-weight-medium text-dark"><i
                                                class="icon-screen-desktop mr-2 text-success"></i> {{trans('Devices')}}:</span>
                                    </li>

                                    @foreach(json_decode($redirect->devices_value,true) as $keyDevice=>$valueDevice)
                                        <li class="my-3">
                                            <span><i class="icon-check mr-2 text-success"></i> {{$keyDevice}} : <span
                                                    class="font-weight-bold">{{$valueDevice ?? 'N/A'}}</span>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                                <ul class="list-style-none mt-4">
                                    <li class="my-2 border-bottom pb-3">
                                        <span class="font-weight-medium text-dark"><i
                                                class="fa fa-globe mr-2 text-warning"></i> {{trans('Country')}}:</span>
                                    </li>

                                    @foreach(json_decode($redirect->country_value,true) as $keyCountry=>$valueCountry)
                                        <li class="my-3 ">
                                            <span><i class="icon-check mr-2 text-warning"></i> {{$keyCountry}} :
                                                <span class="font-weight-medium">{{$valueCountry}}</span>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                            <div class="col-md-4 ">
                                <ul class="list-style-none">
                                    <li class="my-2 border-bottom pb-3">
                                        <span class="font-weight-medium text-dark">
                                            <i class="icon-doc mr-2 text-primary"></i> {{trans('HTML')}}:</span>
                                    </li>
                                    <li class="my-3">
                                        {!! $redirect->redirect_html !!}
                                    </li>
                                </ul>
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
        document.getElementById('editSpan').addEventListener('click', function () {
            window.location.href = "{{ route('admin.redirect.edit', ['id' => $redirect->id]) }}";
        });
    </script>
@endpush


