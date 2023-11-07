@extends('admin.layouts.app')
@section('title',trans($title))

@section('content')
    <div class="m-0 m-md-4 my-4 m-md-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary shadow">
                    <div class="card-body">
                        <h4 class="card-title">@lang('Add Information')</h4>
                        <form enctype="multipart/form-data" id="formAdd_redirect">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('User Name')</label>
                                        <select class="form-control" name="user_id" id="user_id">
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Domain')</label>
                                        <select class="form-control" name="domain_id[]" id="domain_id" multiple required></select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Name')</label>
                                        <input class="form-control" type="text" name="redirect_name" id="redirect_name" value="{{old('redirect_name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Url')</label>
                                        <input class="form-control" type="text" name="redirect_url" id="redirect_url" value="{{ old('redirect_url') }}" required>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Url Block')</label>
                                        <input class="form-control" type="text" name="redirect_url_block" value="{{ old('redirect_url_block') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Exp Date')</label>
                                        <input class="form-control" type="date" name="exp_date_at" id="exp_date_at" value="{{ old('exp_date_at') }}">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('HTML')</label>
                                        <textarea class="form-control" type="text" name="redirect_html" rows="10"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row manageAds">
                                        @php
                                            $ads_ids = [
                                                'app_id' => 'App ID',
                                                'open_ads_id' => 'Open Aps ID',
                                                'banner_ads_id' => 'Banner Ads ID',
                                                'interstitial_ads_id' => 'Interstitial Ads ID',
                                                'native_ads_id' => 'Native Ads ID',
                                                'rewarded_ads_id' => 'Rewarded Ads ID'
                                            ];
                                        @endphp

                                        @foreach($ads_ids as $key => $label)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ $label }}</label>
                                                    <input class="form-control" type="text" name="manage_ads[{{ $key }}]" id="{{ $key }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-3 justify-content-between">
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Is Devices')</label>
                                        <div class="custom-switch-btn">
                                            <input type='hidden' value='1' name='isDevices_status'>
                                            <input type="checkbox" name="isDevices_status" class="custom-switch-checkbox" id="isDevices_status"
                                                   value="0" checked>
                                            <label class="custom-switch-checkbox-label" for="isDevices_status">
                                                <span class="custom-switch-checkbox-inner"></span>
                                                <span class="custom-switch-checkbox-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Is Webview')</label>
                                        <div class="custom-switch-btn">
                                            <input type='hidden' value='1' name='isWebview_status'>
                                            <input type="checkbox" name="isWebview_status" class="custom-switch-checkbox" id="isWebview_status"
                                                   value="0" checked>
                                            <label class="custom-switch-checkbox-label" for="isWebview_status">
                                                <span class="custom-switch-checkbox-inner"></span>
                                                <span class="custom-switch-checkbox-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Ads')</label>
                                        <div class="custom-switch-btn">
                                            <input type='hidden' value='1' name='isAds_status'>
                                            <input type="checkbox" name="isAds_status" class="custom-switch-checkbox" id="isAds_status"
                                                   value="0" checked>
                                            <label class="custom-switch-checkbox-label" for="isAds_status">
                                                <span class="custom-switch-checkbox-inner"></span>
                                                <span class="custom-switch-checkbox-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <a href="javascript:void(0)" class="btn btn-success float-right mt-3" id="generateCountry"><i
                                                class="fa fa-plus-circle"></i> Add Country</a>
                                    </div>
                                </div>

                            </div>

                            <div class="row addedCountry">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input name="country_value[iso_code][]" class="form-control" type="text" value="other" readonly>
                                            <input name="country_value[url][]" class="form-control " type="text" placeholder="https://example.com">
                                            <span class="input-group-btn">
                                        </span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row addedDevices">
                                @php
                                    $devices_arr = ['AndroidOS','iOS','Windows','Other'];
                                @endphp
                                @foreach($devices_arr as $device)
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label>Url {{$device}}</label>
                                            <input class="form-control" type="text" name="devices_value[{{$device}}]">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="submit-btn-wrapper mt-md-3  text-center text-md-left">
                                <button type="submit"
                                        class=" btn btn-rounded btn-primary btn-block">
                                    <span>@lang('Create Redirect')</span></button>
                            </div>
                        </form>
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

            var maxSelectionLength = 0;

            setMinAndValueDate('exp_date_at', 7);

            applySelect2('#user_id', "Select User or Enter", '{{ route('api.getUsers') }}');

            $('#user_id').change(function() {
                var user_id = $(this).val();
                $('#domain_id').val(null).trigger('change');

                if (user_id.length > 0) {
                    $('#domain_id').select2({
                        width: '100%',
                        placeholder: "",
                        ajax: {
                            url: '{{ route('api.getDomainsByUser') }}',
                            dataType: 'json',
                            type: "POST",
                            data: function (params) {
                                return {
                                    user_id: user_id,
                                    q: params.term, // search term
                                    page: params.page
                                };
                            },
                            processResults: function (data) {
                                maxSelectionLength = data[0].user.limit_domain_redirect;
                                return {
                                    results: $.map(data, function (item) {
                                        console.log(item)
                                        generateRandomString('redirect_url',15);
                                        generateRandomString('redirect_name',15,item.user.username+'_');


                                        return {
                                            text: item.name,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                        },
                        initSelection: function (element, callback) {
                            var data = [];

                            $(element.val()).each(function () {
                                data.push({id: this, text: this});
                            });
                            callback(data);
                        }
                    }).on('select2:select', function (e) {
                        var data = e.params.data;
                        $(this).select2('destroy').select2({
                            maximumSelectionLength: maxSelectionLength
                        }).trigger('change.select2');
                    });
                }
            });

            $("#generateCountry").on('click', function () {
                var form = `<div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <select name="country_value[iso_code][]"  class="form-control">
                                        @foreach($countries as $country)
                                            <option value="{{$country->short_code}}">{{$country->name}}</option>
                                        @endforeach()
                                        </select>
                                        <input name="country_value[url][]" class="form-control " type="text" value="" placeholder="{{trans('Field Name')}}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger delete_desc" type="button">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div> `;
                $('.addedCountry').append(form)
                $('select').select2();
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.input-group').parent().remove();
            });

            var isDevicesStatus = $('#isDevices_status').prop('checked');

            if (isDevicesStatus) {
                $('#generateCountry').show();
                $('.addedCountry').show();
                $('.addedDevices').hide();
            } else {
                $('#generateCountry').hide();
                $('.addedCountry').hide();
                $('.addedDevices').show();
            }

            $('#isDevices_status').change(function () {
                var isChecked = $(this).prop('checked');
                if (isChecked) {
                    $('#generateCountry').show();
                    $('.addedCountry').show();
                    $('.addedDevices').hide();
                } else {
                    $('#generateCountry').hide();
                    $('.addedCountry').hide();
                    $('.addedDevices').show();
                }
            });


            var isAds_status = $('#isAds_status').prop('checked');

            if (isAds_status) {
                $('.manageAds').hide();
            } else {
                $('.manageAds').show();
            }

            $('#isAds_status').change(function () {
                var isChecked = $(this).prop('checked');
                if (isChecked) {
                    $('.manageAds').hide();
                } else {
                    $('.manageAds').show();
                }
            });

            $('#formAdd_redirect').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formAdd_redirect")[0]);
                    $.ajax({
                        data: formData,
                        url: "{{ route('admin.redirect.store') }}",
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    Notiflix.Notify.Failure(data.errors[count]);
                                }
                            }
                            if(data.success){
                                Notiflix.Notify.Success(data.success);
                                window.location.href = "{{ route('admin.redirect') }}";
                            }
                        },
                    });

            });

        });

    </script>
@endpush
