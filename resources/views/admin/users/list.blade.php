@extends('admin.layouts.app')
@section('title')
    @lang("User List")
@endsection

@section('button')
    @if(adminAccessRoute(config('role.user_management.access.edit')))
        <div class="dropdown mb-2 text-right">
            <button class="btn btn-sm  btn-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span><i class="fas fa-bars pr-2"></i> @lang('Action')</span>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <button class="dropdown-item" type="button" data-toggle="modal"
                        data-target="#all_active">@lang('Active')</button>
                <button class="dropdown-item" type="button" data-toggle="modal"
                        data-target="#all_inactive">@lang('Inactive')</button>
            </div>
        </div>
    @endif
@endsection

@section('content')

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered"  id="tableUsers">
                    <thead class="thead-primary">
                    <tr>
                        @if(adminAccessRoute(config('role.user_management.access.edit')))
                        <th scope="col" class="text-center">
                            <input type="checkbox" class="form-check-input check-all tic-check" name="check-all"
                                   id="check-all">
                            <label for="check-all"></label>
                        </th>
                        @endif
                        <th scope="col">@lang('Name')</th>
                        <th scope="col">@lang('Type')</th>
                        <th scope="col">@lang('IP')</th>
                        <th scope="col">@lang('Status')</th>
                        <th style="width: 10%" scope="col">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="modal fade" id="all_active" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Active User Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang("Are you really want to active the User's")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('No')</span></button>
                    <form action="" method="post">
                        @csrf
                        <a href="" class="btn btn-primary active-yes"><span>@lang('Yes')</span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="all_inactive" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('DeActive User Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang("Are you really want to Inactive the User's")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('No')</span></button>
                    <form action="" method="post">
                        @csrf
                        <a href="" class="btn btn-primary inactive-yes"><span>@lang('Yes')</span></a>
                    </form>
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

            $(document).on('click', '#check-all', function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });

            $(document).on('change', ".row-tic", function () {
                let length = $(".row-tic").length;
                let checkedLength = $(".row-tic:checked").length;
                if (length == checkedLength) {
                    $('#check-all').prop('checked', true);
                } else {
                    $('#check-all').prop('checked', false);
                }
            });

            //dropdown menu is not working
            $(document).on('click', '.dropdown-menu', function (e) {
                e.stopPropagation();
            });

            //multiple active
            $(document).on('click', '.active-yes', function (e) {
                e.preventDefault();
                var allVals = [];
                $(".row-tic:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });

                var strIds = allVals;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{ route('admin.user-multiple-active') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();

                    },
                });
            });

            //multiple deactive
            $(document).on('click', '.inactive-yes', function (e) {
                e.preventDefault();
                var allVals = [];
                $(".row-tic:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });

                var strIds = allVals;
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{ route('admin.user-multiple-inactive') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();

                    }
                });
            });


            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var tableUsers = $('#tableUsers').DataTable({
                    displayLength: {{config('basic.paginate')}},
                    processing: false,
                    serverSide: true,

                    scrollY: viewportHeight+'px',
                    ajax: {
                        url: "{{ route('admin.users.getIndex')}}",
                        type: "POST",
                    },
                    columns: [
                        {
                            data: 'id', name: 'id',
                            orderable: false,
                            render: function (data, type, full, meta) {
                                return '<input type="checkbox" id="chk-'+data+'"'+
                                'class="form-check-input row-tic tic-check" name="check" value="'+data+'"'+
                                'data-id="'+data+'">'+
                                    '<label for="chk-'+data+'"></label>';
                            },
                            className: "text-center",
                        },
                        {data: 'fullname', name: 'fullname'},
                        {data: 'list_type_id', name: 'list_type_id'},
                        {data: 'last_login_ip', name: 'last_login_ip'},
                        {data: 'status', name: 'status'},

                        {data: 'action', name: 'action', className: "text-center", orderable: false, searchable: false},
                    ],
                    order: [[ 3, 'desc' ]],
                    drawCallback: function (settings) {
                        $('.user-type').click(function() {
                            // Lấy giá trị của data-user và data-type từ thẻ được click
                            var user = $(this).data('user');
                            var type = $(this).data('type_id');
                            var hasSuccessClass = $(this).hasClass('badge-success') ? 'remove':'add';
                            changeListTypeUserAjax(type,hasSuccessClass,user )
                            if (hasSuccessClass === 'remove') {
                                $(this).removeClass('badge-success').addClass('badge-dark');
                            } else {
                                $(this).removeClass('badge-dark').addClass('badge-success');
                            }

                        });
                    },

                });


            });


        $(document).on('click', '.loginAccount', function () {
            var route = $(this).data('route');
            $('.loginAccountAction').attr('action', route)
        });

    </script>
@endpush
