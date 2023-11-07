@extends('admin.layouts.app')
@section('title',trans($title))
@section('button')
    @can('admin.staff.create')
        <div class="d-flex justify-content-end m-2 text-right">
            <button class="btn btn-primary btn-sm mr-2" id="createStaff">
                <i class="fa fa-plus"></i> {{trans('Add New')}}
            </button>
        </div>
    @endcan
@endsection
@section('content')

    @can('admin.staff')
        <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered" id="tableStaff">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('ID')</th>
                        <th scope="col">@lang('Username')</th>
                        <th scope="col">@lang('Name')</th>
                        <th scope="col">@lang('Email')</th>
                        <th scope="col">@lang('Status')</th>
                        <th scope="col">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endcan
    <!-- Modal for Add button -->
    @can('admin.staff.create')
        <div class="modal fade" id="modalStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel">@lang('Manage Admin Role')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <form class="actionRoute" id="formStaff"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="text-dark"> {{trans('Name')}} :</label>
                                <input type="hidden" name="staff_id" id="staff_id">

                                <input class="form-control "
                                       name="name"
                                       id="name"
                                       placeholder="{{trans('Name')}}" value="{{old('name')}}" required autocomplete="off">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="text-dark"> {{trans('Username')}} :</label>
                                <input class="form-control "
                                       name="username"
                                       id="username"
                                       placeholder="{{trans('Username')}}" value="{{old('username')}}"
                                       autocomplete="off"
                                       required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="text-dark"> {{trans('E-Mail')}} :</label>
                                <input class="form-control "
                                       name="email"
                                       id="email"
                                       placeholder="Email Address" value="{{old('email')}}"
                                       autocomplete="off"
                                       required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="text-dark"> {{trans('Password')}} :</label>
                                <input type="password" name="password" id="password" placeholder="Password"
                                       autocomplete="off"
                                       class="form-control " value="{{old('password')}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="text-dark"> {{trans('Select Status')}} :</label>
                                <select name="status" id="event-status"
                                        class="form-control " required>
                                    <option value="1" @if(old('status') == '1') selected @endif>
                                        {{trans('Active')}}
                                    </option>
                                    <option value="0" @if(old('status') == '0') selected @endif>
                                        {{trans('DeActive')}}
                                    </option>
                                </select>
                                <br>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between text-center">
                                        <h5 class="card-title text-center">{{trans('Accessibility')}}</h5>
                                    </div>

                                    <div class="card-body select-all-access">
                                        <div class="form-group">
                                            <label><input type="checkbox" class="selectAll"
                                                          name="accessAll"> {{trans('Select All')}}</label>
                                        </div>

                                        <table class=" table table-hover table-striped table-bordered text-center">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th class="text-left">@lang('Permissions')</th>
                                                <th>@lang('View')</th>
                                                <th>@lang('Add')</th>
                                                <th>@lang('Edit')</th>
                                                <th>@lang('Delete')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach(config('role') as $key => $value)
                                                <tr>
                                                    <td data-label="Permissions"
                                                        class="text-left">{{$value['label']}}</td>
                                                    <td data-label="View">
                                                        @if(!empty($value['access']['view']))
                                                            <input type="checkbox"
                                                                   value="{{join(',',$value['access']['view'])}}"
                                                                   name="access[]"/>
                                                        @endif
                                                    </td>
                                                    <td data-label="Add">
                                                        @if(!empty($value['access']['add']))
                                                            <input type="checkbox"
                                                                   value="{{join(',',$value['access']['add'])}}"
                                                                   name="access[]"/>

                                                        @endif
                                                    </td>
                                                    <td data-label="Edit">
                                                        @if(!empty($value['access']['edit']))
                                                            <input type="checkbox"
                                                                   value="{{join(',',$value['access']['edit'])}}"
                                                                   name="access[]"/>

                                                        @endif
                                                    </td>
                                                    <td data-label="Delete">
                                                        @if(!empty($value['access']['delete']))
                                                            <input type="checkbox"
                                                                   value="{{join(',',$value['access']['delete'])}}"
                                                                   name="access[]"/>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" id="btnStaff" class="btn btn-success">@lang('Save')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    @endcan
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
            $('.selectAll').on('click', function () {
                if ($(this).prop('checked')) {
                    $(this).parents('.select-all-access').find('input[type="checkbox"]').prop('checked', true);
                } else {
                    $(this).parents('.select-all-access').find('input[type="checkbox"]').prop('checked', false);
                }
            });

            const tableStaff = setupDataTable(
                '#tableStaff',
                "{{ route('admin.staff.getIndex')}}",
                [
                    {data: 'id',name: 'id'},
                    {data: 'username', name: 'username'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                [[ 0, 'asc' ]]
            );


            $(document).on('click','#createStaff', function (data){
                $('#modalStaff').modal('show');
                $('#btnStaff').val('createStaff');
                $('#staff_id').val('');
                $('#formStaff').trigger("reset");
            });

            $(document).on('click','.editStaff', function (data){
                var id = $(this).data("id");
                $('#modalStaff').modal('show');
                $('#btnStaff').val('editStaff');

                $.ajax({
                    type: "get",
                    url: "{{ route("admin.staff.edit") }}",
                    data: {
                        'id' : id
                    },
                    success: function (data) {
                        var ajaxLabels = data.permissions.map(permission => permission.name);
                        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                            var values = checkbox.value.split(',');
                            if (values.some(value => ajaxLabels.includes(value))) {
                                checkbox.checked = true;
                            }
                            else {
                                checkbox.checked = false;
                            }
                        });

                        $('#staff_id').val(data.id);
                        $('#name').val(data.name);
                        $('#username').val(data.username);
                        $('#email').val(data.email);
                        $('#ip_address').val(data.ip_address);
                        $('#password').val('');
                        $("#event-status").prop("checked", data.status !== 1);
                        },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            });

            $('#formStaff').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formStaff")[0]);
                var url = "";
                var successCallback = null;


                if ($('#btnStaff').val() == 'createStaff') {
                    url = "{{ route('admin.staff.store') }}";
                }
                if ($('#btnStaff').val() == 'editStaff') {
                    url = "{{ route('admin.staff.update')}}";
                }
                successCallback = function () {
                    $('#formStaff').trigger("reset");
                    $('#modalStaff').modal('hide');
                    $('#tableStaff').DataTable().draw();
                };
                handleAjaxRequest(formData, url, successCallback);
            });


        })

    </script>
@endpush
