@extends('admin.layouts.app')
@section('title',trans($title))
@section('button')
    @if(adminAccessRoute(config('role.redirect_management.access.add')))
        <div class="d-flex justify-content-end m-2 text-right">
            <a href="{{route('admin.redirect.create')}}">
                <button class="btn btn-primary btn-sm mr-2" id="createRedirect">
                    <i class="fa fa-plus"></i> {{trans('Add New')}}
                </button>
            </a>
        </div>
    @endif
@endsection

@section('search_button')
    <div class="row">
        <div class="col-6">
            <div class="customize-input">
                <label>@lang('Search Name')</label>
                <select class="form-control" name="user_id_search[]" id="user_id_search" multiple>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="customize-input">
                <label>@lang('Search Domain')</label>
                <select class="form-control" name="domain_search[]" id="domain_search" multiple>
                </select>
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered" id="tableRedirects">
                    <thead class="thead-primary">
                    <tr>
                        <th scope="col">@lang('ID')</th>
                        <th scope="col">@lang('Name')</th>
                        <th scope="col">@lang('Domain')</th>
                        <th scope="col">@lang('User Name')</th>
                        <th scope="col">@lang('Is Devices')</th>
                        <th scope="col">@lang('Exp Date')</th>
                        <th scope="col">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
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

            var redirectTable = $('#tableRedirects').DataTable({
                displayLength: 50,
                lengthMenu: [25, 50, 100, 200, 500, 1000],
                processing: false,
                serverSide: true,
                scrollY: viewportHeight+'px',
                ajax: {
                    url: "{{ route('admin.redirect.getIndex')}}",
                    type: "POST",
                    data: function(d) {
                        // Lấy giá trị đã chọn từ trường select
                        var selectedUserName = $('#user_id_search').val();
                        var selectedDomainName = $('#domain_search').val();
                        // Thêm giá trị user_name vào dữ liệu gửi đi
                        d.user_id = selectedUserName;
                        d.domain_id = selectedDomainName;
                    }
                },
                columns: [

                    {data: 'id', name: 'id'},
                    {data: 'redirect_name', name: 'redirect_name'},
                    {data: 'domain_id', name: 'domain_id'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'is_devices', name: 'is_devices'},
                    {data: 'exp_date_at', name: 'exp_date_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[ 0, 'desc' ]],
                drawCallback: function (settings) {
                    copyValueName('.copyButtonName','data-full_url')
                },


            });

            copyValueName('.copyButtonName','data-full_url')
            $(document).on('click','.deleteRedirect', function (data){
                var _id = $(this).data("id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#02a499",
                    cancelButtonColor: "#ec4561",
                    confirmButtonText: "Yes, delete it!"
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "{{route('admin.redirect.delete')}}",
                            data:{
                                'id': _id
                            },
                            success: function (data) {
                                redirectTable.draw();
                                Notiflix.Notify.Success(data.success);
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });

            applySelect2('#user_id_search', "", '{{ route('api.getUsers') }}');
            applySelect2('#domain_search', "", '{{ route('api.getDomains') }}');

            $('#user_id_search').on('change', function() {
                var user_id = $(this).val();
                if (user_id.length > 0) {
                    $('#domain_search').select2({
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
                                return {
                                    results: $.map(data, function (item) {
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
                    });
                }else {
                    applySelect2('#domain_search', "", '{{ route('api.getDomains') }}');
                }
                redirectTable.ajax.reload();
            });

            $('#domain_search').on('change', function() {
                redirectTable.ajax.reload();
            });
        });

    </script>
@endpush
