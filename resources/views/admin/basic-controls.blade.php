@extends('admin.layouts.app')
@section('title')
    @lang('Basic Controls')
@endsection
@section('content')
    <div class="bd-callout bd-callout-warning m-0 m-md-4 my-4 m-md-0 ">
        <i class="fas fa-info-circle mr-2 text-info"></i> @lang("If you get 500(server error) for some reason, please turn on <b>Debug Mode</b> and try again. Then you can see what was missing in your system.") </div>
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0">
        <div class="card-body">

            <form method="post" action="" novalidate="novalidate" class="needs-validation base-form">
                @csrf
                <div class="row">
                    <div class="form-group  col-sm-6 col-md-4 col-lg-3">
                        <label class="text-dark">@lang('Site Title')</label>
                        <input type="text" name="site_title"
                               value="{{ old('site_title') ?? $control->site_title ?? 'Site Title' }}"
                               class="form-control ">

                        @error('site_title')
                        <span class="text-danger">{{ trans($message) }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-6 col-md-4 col-lg-3">
                        <label class="text-dark">@lang('TimeZone')</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="time_zone">
                            <option hidden>{{ old('time_zone', $control->time_zone)?? 'Select Time Zone' }}</option>
                            @foreach ($control->time_zone_all as $time_zone_local)
                                <option value="{{ $time_zone_local }}">@lang($time_zone_local)</option>
                            @endforeach
                        </select>

                        @error('time_zone')
                        <span class="text-danger">{{ trans($message) }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-6 col-md-4 col-lg-3">
                        <label class="text-dark">@lang('Paginate Per Page')</label>
                        <input type="text" name="paginate" value="{{ old('paginate') ?? $control->paginate ?? '2' }}"
                               required="required" class="form-control ">
                        @error('paginate')
                        <span class="text-danger">{{ trans($message) }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-6 col-md-4 col-lg-3">
                        <label class="text-dark">{{trans('Session Expired')}}</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" value="{{ old('session_expire') ?? $control->session_expire ?? '' }}" name="session_expire">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    {{trans('Minutes')}}
                                </span>
                            </div>
                        </div>

                        @error('session_expire')
                        <span class="text-danger">{{ trans($message) }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-6 col-md-3 col-lg-3">
                        <label class="text-dark">@lang('Strong Password')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='strong_password'>
                            <input type="checkbox" name="strong_password" class="custom-switch-checkbox"
                                   id="strong_password"
                                   value="0" {{($control->strong_password == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="strong_password">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-6 col-md-3 col-lg-3">
                        <label class="text-dark">@lang('Registration')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='registration'>
                            <input type="checkbox" name="registration" class="custom-switch-checkbox"
                                   id="registration"
                                   value="0" {{($control->registration == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="registration">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-3 col-lg-3">
                        <label class="font-weight-bold">@lang('Debug Mode')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='error_log'>
                            <input type="checkbox" name="error_log" class="custom-switch-checkbox"
                                   id="error_log"
                                   value="0" <?php if ($control->error_log == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="error_log">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-6 col-md-3 col-lg-3">
                        <label class="d-block">@lang('Maintenance Mode')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='maintenance'>
                            <input type="checkbox" name="maintenance" class="custom-switch-checkbox"
                                   id="maintenance"
                                   value="0" {{($control->maintenance  == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="maintenance">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <span class="btn waves-effect waves-light btn-rounded btn-warning btn-block mt-3 btn_clear_user_logs"><span><i class="fas fa-user-circle pr-2"></i> @lang('Clear User Logs ')</span></span>
                    </div>
                    <div class="form-group col-lg-3">
                        <span class="btn waves-effect waves-light btn-rounded btn-warning btn-block mt-3 btn_clear_redirects_logs"><span><i class="fas fa-recycle pr-2"></i> @lang('Clear Redirects Logs')</span></span>
                    </div>
                    <div class="form-group col-lg-6">
                        <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i class="fas fa-save pr-2"></i> @lang('Save Changes')</span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('js')
    <script>
        "use strict";
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('select').select2({
                selectOnClose: true
            });

            $(document).on('click','.btn_clear_user_logs', function (){
                $.ajax({
                    type: "post",
                    url: "{{route('admin.basic-controls.clearUserLogs')}}",
                    success: function (data) {
                        if(data.success){
                            Notiflix.Notify.Success(data.success);
                        }
                        if(data.error){
                            Notiflix.Notify.Failure(data.error);
                        }

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $(document).on('click','.btn_clear_redirects_logs', function (){
                $.ajax({
                    type: "post",
                    url: "{{route('admin.basic-controls.clearRedirectLogs')}}",
                    success: function (data) {
                        if(data.success){
                            Notiflix.Notify.Success(data.success);
                        }
                        if(data.error){
                            Notiflix.Notify.Failure(data.error);
                        }                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        });
    </script>
@endpush
