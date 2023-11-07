@extends('admin.layouts.app')
@section('title',trans($title))
@section('search_button')
    <div class="row">
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
                <table class="table table-hover table-striped table-bordered" id="tableRedirectHistory">
                    <thead class="thead-primary">
                    <tr>
                        <th scope="col">@lang('STT')</th>
                        <th scope="col">@lang('Url')</th>
                        <th scope="col">@lang('IP Address')</th>
                        <th scope="col">@lang('Country')</th>
                        <th scope="col">@lang('Devices')</th>
                        <th scope="col">@lang('Is Robot')</th>
                        <th scope="col">@lang('User Agent')</th>
                        <th scope="col">@lang('Count')</th>
                        <th scope="col">@lang('Create_at')</th>
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

            var redirectDetailTable = $('#tableRedirectHistory').DataTable({
                displayLength: 50,
                lengthMenu: [25, 50, 100, 200, 500, 1000],
                processing: false,
                serverSide: true,

                scrollY: viewportHeight+'px',
                ajax: {
                    url: "{{ route('admin.redirect_history.getIndex')}}",
                    type: "POST",
                    data: function(d) {
                        // Lấy giá trị đã chọn từ trường select
                        var selectedDomainName = $('#domain_search').val();
                        // Thêm giá trị user_name vào dữ liệu gửi đi
                        d.domain_id = selectedDomainName;
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        render: function (data, type, full, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data: 'url_full', name: 'url_full'},
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'country', name: 'country'},
                    {data: 'platform_name', name: 'platform_name'},
                    {data: 'is_robot', name: 'is_robot'},
                    {data: 'device_name_full', name: 'device_name_full'},
                    {data: 'count', name: 'count'},
                    {data: 'updated_at', name: 'updated_at'},
                ],
                order: [[ 8, 'desc' ]],
                drawCallback: function (settings) {
                    copyValueName('.copyButtonName','data-full_url')
                },
            });
            applySelect2('#domain_search', "", '{{ route('api.getDomains') }}');
            $('#domain_search').on('change', function() {
                redirectDetailTable.ajax.reload();
            });
        });

    </script>
@endpush
