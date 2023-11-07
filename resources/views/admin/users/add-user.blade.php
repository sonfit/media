@extends('admin.layouts.app')
@section('title', trans('Add User'))

@section('content')
    <div class="m-0 m-md-4 my-4 m-md-0">
        <div class="row">

            <div class="col-lg-12">

                <div class="card card-primary shadow">
                    <div class="card-body">
                        <h4 class="card-title">@lang('Add Information')</h4>
                        <form method="post" action="{{ route('admin.users.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Full Name')</label>
                                        <input class="form-control" type="text" name="fullname"
                                               value="{{old('fullname')}}"
                                                >
                                        @error('fullname')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Username')</label>
                                        <input class="form-control" type="text" name="username"
                                               value="{{ old('username') }}"  >
                                        @error('username')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Email')</label>
                                        <input class="form-control" type="email" name="email" value="{{old('email') }}"
                                                >
                                        @error('email')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Phone Number')</label>
                                        <input class="form-control" type="text" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Password')</label>
                                        <div class="input-group mb-3">
                                            <input id="password_confirmation" type="password"
                                                   class="form-control " aria-label="Default"
                                                   aria-describedby="inputGroup-sizing-default"
                                                   name="password" value="{{old('password') ?? '123456'}}" placeholder="@lang('Password')"
                                                   autocomplete="off">
                                            <div class="input-group-append">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">
                                                        <a href="javascript:void(0)"
                                                           class="text-dark clickShowPassword">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </a>
                                                    </span>
                                            </div>
                                        </div>

                                        @error('password')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Confirm Password')</label>
                                        <div class="input-group mb-3">
                                            <input id="password_confirmation" type="password"
                                                   class="form-control " aria-label="Default"
                                                   aria-describedby="inputGroup-sizing-default"
                                                   name="password_confirmation" value="{{old('password_confirmation') ?? '123456'}}" placeholder="@lang('Confirm Password')"
                                                   autocomplete="off">
                                            <div class="input-group-append">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">
                                                        <a href="javascript:void(0)"
                                                           class="text-dark clickShowPassword">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </a>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Limit Domain') (Giới hạn Domain)</label>
                                        <input class="form-control" type="number" name="limit_domain" value="{{ old('limit_domain')??1 }}">
                                        @error('limit_domain')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Limit Redirect') (Giới hạn Bọc link)</label>
                                        <input class="form-control" type="number" name="limit_redirect" value="{{ old('limit_redirect') ?? 1 }}">
                                        @error('limit_redirect')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Limit Domain Redirect') (Giới hạn Domain trong Bọc link)</label>
                                        <input class="form-control" type="number" name="limit_domain_redirect" value="{{ old('limit_domain_redirect') ?? 1 }}">
                                        @error('limit_domain_redirect')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label>@lang('Status')</label>
                                    <div class="custom-switch-btn w-md-80">
                                        <input type='hidden' value='1' name='status'>
                                        <input type="checkbox" name="status" class="custom-switch-checkbox"
                                               id="status">
                                        <label class="custom-switch-checkbox-label" for="status">
                                            <span class="custom-switch-checkbox-inner"></span>
                                            <span class="custom-switch-checkbox-switch"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label>@lang('2FA Security')</label>
                                    <div class="custom-switch-btn w-md-80">
                                        <input type='hidden' value='0' name='two_fa_verify'>
                                        <input type="checkbox" name="two_fa_verify"
                                               class="custom-switch-checkbox"
                                               id="two_fa_verify" checked>
                                        <label class="custom-switch-checkbox-label" for="two_fa_verify">
                                            <span class="custom-switch-checkbox-inner"></span>
                                            <span class="custom-switch-checkbox-switch"></span>
                                        </label>
                                    </div>
                                </div>




                            </div>
                            <div class="submit-btn-wrapper mt-md-3  text-center text-md-left">
                                <button type="submit"
                                        class=" btn btn-rounded btn-primary btn-block">
                                    <span>@lang('Create User')</span></button>
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
        $(document).on('click', '.clickShowPassword', function () {
            var passInput = $(this).closest('.input-group').find('input');
            if (passInput.attr('type') === 'password') {
                $(this).children().addClass('fa-eye');
                $(this).children().removeClass('fa-eye-slash');
                passInput.attr('type', 'text');
            } else {
                $(this).children().addClass('fa-eye-slash');
                $(this).children().removeClass('fa-eye');
                passInput.attr('type', 'password');
            }
        })
    </script>
@endpush
