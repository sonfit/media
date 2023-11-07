@extends('admin.layouts.app')
@section('title')
    @lang($user->username)
@endsection
@section('content')

    <div class="m-0 m-md-4 my-4 m-md-0">
        <div class="row">

            <div class="col-lg-4">
                <div class="card card-primary ">
                    <div class="card-body">
                        <h4 class="card-title ">@lang('Profile')</h4>
                        <div class="form-group text-center">
                            <img class="rounded mx-auto d-block w-50"
                                 src="{{getFile(config('location.user.path').$user->image) }}"
                                 alt="preview image">
                        </div>
                        <h3> @lang(ucfirst($user->username))</h3>
                        <p>@lang('Joined At') @lang($user->created_at->format('d M,Y h:i A')) </p>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">@lang('User information')</h4>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Email')
                                <span>{{ $user->email }}</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Username')
                                <span>{{ $user->username }}</span></li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Status')
                                <span
                                    class="badge badge-{{($user->status==1) ? 'success' :'danger'}} success badge-pill">{{($user->status==1) ? trans('Active') : trans('Inactive')}}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Types')
                                @foreach($types as $type)
                                    <a href="javascript:void(0)"
                                       data-id="{{$type->id}}"
                                       data-user="{{$user->id}}"
                                       class="badge badge-{{ $user->list_type_id ? (in_array($type->id, $user->list_type_id) ? 'success' : 'dark') : 'dark'}} badge-pill m-1" onclick="changeListTypeUser({{$type->id}},'badge')">{{$type->name}}
                                    </a>
                                @endforeach
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Last Login')
                                <span>{{ diffForHumans($user->last_login) }}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('IP')
                                <span>{{ $user->last_login_ip }}</span>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">@lang('More Information')</h4>


                        <div class="btn-list ">
                            <a href="{{ route('admin.user.loggedIn',$user->id) }}"
                               class="btn btn-info btn-sm">
                                <span class="btn-label"><i class="fas fa-history"></i></span> @lang('Login Logs')
                            </a>

                            <a class="btn btn-info btn-sm loginAccount text-white" type="button"
                                data-toggle="modal"
                                data-target="#signIn"
                                data-route="{{route('admin.user.login-as-user',$user->id)}}">
                             <span class="btn-label">
                                <i class="fas fa-sign-in-alt"></i></span> @lang('Login as User')
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-8">

                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">{{ ucfirst($user->username) }} @lang('Information')</h4>
                        <form id="formEdit_User"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>@lang('Full Name')</label>
                                        <input class="form-control" type="text" name="fullname"
                                               value="{{ $user->fullname }}"
                                               required>
                                        @error('fullname')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>@lang('Username')</label>
                                        <input class="form-control" type="text" name="username"
                                               value="{{ $user->username }}" required>
                                        @error('username')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>@lang('Email')</label>
                                        <input class="form-control" type="email" name="email" value="{{ $user->email }}"
                                               required>
                                        @error('email')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>@lang('Phone Number')</label>
                                        <input class="form-control" type="text" name="phone" value="{{ $user->phone }}">
                                        @error('phone')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>@lang('Limit Domain') (Giới hạn Domain)</label>
                                        <input class="form-control" type="number" name="limit_domain" value="{{ $user->limit_domain }}">
                                        @error('limit_domain')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>@lang('Limit Redirect') (Giới hạn Bọc link)</label>
                                        <input class="form-control" type="number" name="limit_redirect" value="{{ $user->limit_redirect }}">
                                        @error('limit_redirect')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>@lang('Limit Domain Redirect')</label>
                                        <input class="form-control" type="number" name="limit_domain_redirect" value="{{ $user->limit_domain_redirect }}">
                                        @error('limit_domain_redirect')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">

                                    <div class="form-group ">
                                        <label>@lang('Preferred language')</label>

                                        <select name="language_id" id="language_id" class="form-control">
                                            <option value="" disabled>@lang('Select Language')</option>
                                            @foreach($languages as $la)
                                                <option value="{{$la->id}}"
                                                    {{ old('language_id', $user->language_id) == $la->id ? 'selected' : '' }}>@lang($la->name)</option>
                                            @endforeach
                                        </select>

                                        @error('language_id')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label>@lang('Address')</label>
                                        <textarea class="form-control" name="address"
                                                  rows="2">{{ $user->address }}</textarea>
                                        @error('address')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>@lang('Status')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='status'>
                                                <input type="checkbox" name="status" class="custom-switch-checkbox"
                                                       id="status" {{ $user->status == '0' ? 'checked' : '' }} >
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
                                                       id="two_fa_verify" {{ $user->two_fa_verify == '1' ? 'checked' : '' }}>
                                                <label class="custom-switch-checkbox-label" for="two_fa_verify">
                                                    <span class="custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn-wrapper mt-md-3  text-center text-md-left">
                                <button type="submit"
                                        class=" btn btn-rounded btn-primary btn-block">
                                    <span>@lang('Update User')</span></button>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="card card-primary ">
                    <div class="card-body">
                        <h4 class="card-title">@lang('Password Change')</h4>

                        <form method="post" action="{{ route('admin.user.userPasswordUpdate',$user->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label>@lang('New Password')</label>

                                        <div class="input-group mb-3">
                                            <input id="new_password" type="password"
                                                   class="form-control " aria-label="Default"
                                                   aria-describedby="inputGroup-sizing-default"
                                                   name="password" value="{{old('password')}}" placeholder="@lang('New Password')"
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
                                    <div class="form-group ">
                                        <label>@lang('Confirm Password')</label>

                                        <div class="input-group mb-3">
                                            <input id="password_confirmation" type="password"
                                                   class="form-control " aria-label="Default"
                                                   aria-describedby="inputGroup-sizing-default"
                                                   name="password_confirmation" value="{{old('password_confirmation')}}" placeholder="@lang('Confirm Password')"
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


                                        @error('password_confirmation')
                                        <span class="text-danger">{{ trans($message) }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn-wrapper mt-md-3 text-center text-md-left">
                                <button type="submit"
                                        class="btn waves-effect waves-light btn-rounded btn-primary btn-block">
                                    <span>@lang('Update Password')</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Admin Login as a User Modal -->
    <div class="modal fade" id="signIn">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="" class="loginAccountAction" enctype="multipart/form-data">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title">@lang('Sing In Confirmation')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>Are you sure to sign in this account?</p>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class=" btn btn-primary "><span>@lang('Yes')</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        "use strict";
        $(document).ready(function () {

            $('select').select2({
                selectOnClose: true
            });

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


            $('#formEdit_User').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formEdit_User")[0]);
                $.ajax({
                    data: formData,
                    url: "{{ route('admin.user.update') }}",
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
                        }
                    },
                });
            });

        });

        $(document).on('click', '.loginAccount', function () {
            var route = $(this).data('route');
            $('.loginAccountAction').attr('action', route)
        });


    </script>
@endpush


