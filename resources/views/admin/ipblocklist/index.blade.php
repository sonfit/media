@extends('admin.layouts.app')
@section('title',trans($title))
@section('button')

    @can('admin.ipblock.create')
        <div class="d-flex justify-content-end m-2 text-right">
            <button class="btn btn-primary btn-sm mr-2" id="createIplistBlock">
                <i class="fa fa-plus"></i> {{trans('Add New')}}
            </button>

            <button class="btn btn-warning btn-sm" id="bulkIplistBlock">
                <i class="fa fa-desktop"></i> {{trans('Add Bulk')}}
            </button>
        </div>
    @endcan
@endsection

@section('content')
    @can('admin.ipblock')
        <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered" id="tableIplistBlock">
                    <thead class="thead-dark">
                    <tr>
                        <th style="width: 5%" scope="col">@lang('SL')</th>
                        <th scope="col">@lang('Ip address')</th>
                        <th scope="col">@lang('Type')</th>
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
    @if (auth()->user()->hasAnyPermission(['admin.ipblock.create', 'admin.ipblock.edit']))
        <div class="modal fade" id="modalIplistBlock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel">@lang('Manage IP Block')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <form class="actionRoute" id="formIplistBlock"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input type="hidden" name="IplistBlock_id" id="IplistBlock_id">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="text-dark"> {{trans('IP Address')}} :</label>
                                <input class="form-control " name="ip_address" id="ip_address"
                                       placeholder="{{trans('IP Address')}}" value="{{old('ip_address')}}" required>

                            </div>


                            <div class="form-group col-md-12">
                                <label class="text-dark"> {{trans('Select Type')}} :</label>
                                <select name="list_type_id" id="list_type_id"
                                        class="form-control">
                                    <option value="0">
                                        {{trans('None')}}
                                    </option>
                                    @foreach( $list_type as $type)
                                    <option value="{{$type->id}}">
                                        {{trans($type->name)}}
                                    </option>
                                    @endforeach

                                </select>
                                <br>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success" id="btnIplistBlock">@lang('Save')</button>
                    </div>

                </form>


            </div>
        </div>
    </div>

        <div class="modal fade" id="modalBulkIplistBlock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel">@lang('Add IP Bulk')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <form class="actionRoute" id="formBulkIplistBlock"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="text-dark"> {{trans('IP Address')}} :</label>
                                <textarea class="form-control " name="bulk_ip_address" id="bulk_ip_address"
                                         rows="10" required> </textarea>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="text-dark"> {{trans('Upload file')}} :</label>
                                <input type="file" class="form-control " name="file_ip_address" id="file_ip_address">
                            </div>


                            <div class="form-group col-md-12">
                                <label class="text-dark"> {{trans('Select Type')}} :</label>
                                <select name="list_type_id_bulk" id="list_type_id_bulk"
                                        class="form-control">
                                    <option value="0">
                                        {{trans('None')}}
                                    </option>
                                    @foreach( $list_type as $type)
                                        <option value="{{$type->id}}">
                                            {{trans($type->name)}}
                                        </option>
                                    @endforeach

                                </select>
                                <br>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success" id="btnIplistBlock">@lang('Save')</button>
                    </div>

                </form>


            </div>
        </div>
    </div>
    @endif

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

            const tableIplistBlock = setupDataTable(
                '#tableIplistBlock',
                "{{ route('admin.ipblock.getIndex')}}",
                [
                    {data: 'id',name: 'id'},
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'list_type_id', name: 'list_type_id'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                [[ 0, 'asc' ]]
            );

            setupDataTableScrollTop(tableIplistBlock,100);

            $(document).on('click','#createIplistBlock', function (data){
                $('#modalIplistBlock').modal('show');
                $('#btnIplistBlock').val('createIplistBlock');
                $('#IplistBlock_id').val('');
                $('#formIplistBlock').trigger("reset");
                $('#list_type_id').val(0).trigger('change');
            });

            $(document).on('click','#bulkIplistBlock', function (data){
                $('#modalBulkIplistBlock').modal('show');
                $('#btnIplistBlock').val('createIplistBlock');
                $('#IplistBlock_id').val('');
                $('#formIplistBlock').trigger("reset");
            });

            $(document).on('click','.editIplistBlock', function (data){
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/ipblocklist/edit") }}/" + id,
                    success: function (data) {
                        if(data.error){
                            Notiflix.Notify.Failure(data.error);
                        }else{
                            $('#modalIplistBlock').modal('show');
                            $('#btnIplistBlock').val('editIplistBlock');
                            $('#IplistBlock_id').val(data.id);
                            $('#ip_address').val(data.ip_address);
                            $('#list_type_id').val(data.list_type_id).trigger('change');
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            });

            $(document).on('click','.deleteIplistBlock', function (data){
                var _id = $(this).data("id");
                var $element = $(this).closest('tr');
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
                            url: "{{route('admin.ipblock.delete')}}",
                            data:{
                                'id': _id
                            },
                            success: function (data) {
                                if(data.error){
                                    Notiflix.Notify.Failure(data.error);
                                }
                                if(data.success){
                                    Notiflix.Notify.Success(data.success);
                                    $element.fadeOut(400, function () {
                                        $(this).remove();
                                    });
                                }
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });

            $('#formIplistBlock').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formIplistBlock")[0]);
                var url = "";
                var successCallback = null;


                if ($('#btnIplistBlock').val() == 'createIplistBlock') {
                    url = "{{ route('admin.ipblock.store') }}";
                }
                if ($('#btnIplistBlock').val() == 'editIplistBlock') {
                    url = "{{ route('admin.ipblock.update')}}";
                }
                successCallback = function () {
                    $('#formIplistBlock').trigger("reset");
                    $('#modalIplistBlock').modal('hide');
                    $('#tableIplistBlock').DataTable().draw();
                };
                handleAjaxRequest(formData, url, successCallback);
            });

            $('#formBulkIplistBlock').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formBulkIplistBlock")[0]);
                var  url = "{{route('admin.ipblock.bulk_store') }}";
                var successCallback = null;
                successCallback = function () {
                    $('#formBulkIplistBlock').trigger("reset");
                    $('#modalBulkIplistBlock').modal('hide');
                    $('#tableIplistBlock').DataTable().draw();
                };
                handleAjaxRequest(formData, url, successCallback);
            });

            const ipInput = document.querySelector("#ip_address");

            ipInput.addEventListener("input", () => {
                const ip = ipInput.value;
                const parts = ip.split(".");

                if (parts.length > 3) {
                    Notiflix.Notify.Warning("Địa chỉ IP không hợp lệ. Vui lòng nhập không quá 4 phần.");
                    parts.pop();
                }

                for (let i = 0; i < parts.length; i++) {
                    if (parts[i] === "") continue;
                    if (!parts[i].match(/^[0-9]+$/) || parts[i] < 0 || parts[i] > 255) {
                        parts[i] = parts[i].substring(0, parts[i].length - 1);
                    }
                }

                const newIp = parts.join(".");

                if (newIp !== ip) {
                    Notiflix.Notify.Failure("IP không hợp lệ. Vui lòng nhập lại.");

                    ipInput.value = newIp;
                    ipInput.focus();
                }
            });

        })

    </script>
@endpush
