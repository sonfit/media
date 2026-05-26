@extends('admin.layouts.app')
@section('title',trans($title))

@section('content')
    @can('admin.domain')
        <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" id="tableDomainLoginLogs">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">@lang('Domain')</th>
                            <th scope="col">@lang('Ip Address')</th>
                            <th scope="col">@lang('Device Name')</th>
                            <th scope="col">@lang('Platform Name')</th>
                            <th scope="col">@lang('Country')</th>
                            <th scope="col">@lang('Count')</th>
                            <th scope="col">@lang('Time')</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan

    @can('admin.ipblock.create')
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
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="text-dark"> {{trans('IP Address')}} :</label>
                                    <input class="form-control " name="ip_address" id="add_ip_address"
                                           placeholder="{{trans('IP Address')}}" required>

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

            const tableDomainLoginLogs = setupDataTable(
                '#tableDomainLoginLogs',
                "{{ route('admin.domainLoginLogs.getIndex')}}",
                [
                    {data: 'domain_id', name: 'domain_id'},
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'device_name', name: 'device_name'},
                    {data: 'platform_name', name: 'platform_name'},
                    {data: 'country', name: 'country'},
                    {data: 'count', name: 'count'},
                    {data: 'updated_at', name: 'updated_at'},
                ],
                [[ 6, 'desc' ]],
                null,
            );
            setupDataTableScrollTop(tableDomainLoginLogs,100);

            $(document).on('click','.addIplistBlock', function (data){
                $('#modalIplistBlock').modal('show');
                $('#formIplistBlock').trigger("reset");
                $('#list_type_id').val(0).trigger('change');
                $('#add_ip_address').val($(this).text());
            });

            $('#formIplistBlock').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formIplistBlock")[0]);
                var  url = "{{ route('admin.ipblock.store') }}";

                var successCallback = function () {
                    $('#formIplistBlock').trigger("reset");
                    $('#modalIplistBlock').modal('hide');
                    tableDomainLoginLogs.draw();
                };
                handleAjaxRequest(formData, url, successCallback);
            });

            $(document).on('click','.removeIplistBlock', function (data){
                var _id = $(this).data("id_ip");
                var $element = $(this).closest('tr');
                var row = tableDomainLoginLogs.row( $element ).data();
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
                                    tableDomainLoginLogs.draw();

                                }
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });

        })

    </script>
@endpush
