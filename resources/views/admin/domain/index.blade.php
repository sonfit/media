@extends('admin.layouts.app')
@section('title',trans($title))
@section('button')

    @can('admin.domain.create')
        <div class="d-flex justify-content-end m-2 text-right">
            <button class="btn btn-primary btn-sm mr-2" id="createDomain">
                <i class="fa fa-plus"></i> {{trans('Add New')}}
            </button>
        </div>
    @endcan
@endsection
@section('content')
    @can('admin.domain')
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered" id="tableDomain">
                    <thead class="thead-dark">
                    <tr>
                        <th style="width: 5%" scope="col">@lang('SL')</th>
                        <th scope="col">@lang('Domain')</th>
                        <th style="width: 50%" scope="col">@lang('Project AIO')</th>
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
    @endcan

    <!-- Modal for Add button -->
    @if (auth()->user()->hasAnyPermission(['admin.domain.create', 'admin.domain.edit']))
        @include('admin.domain.components.modal')
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

            const tableDomain = setupDataTable(
                '#tableDomain',
                "{{ route('admin.domain.getIndex')}}",
                [
                    {data: 'id', name: 'id'},
                    {data: 'domain_web', name: 'domain_web'},
                    {data: 'domain_project', name: 'domain_project'},
                    {data: 'is_publish', name: 'is_publish'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                [[ 0, 'asc' ]]
            );
            setupDataTableScrollTop(tableDomain,100);

            $(document).on('click','.deleteDomain', function (data){
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
                            {{--url: "{{ asset("ipblocklist/delete") }}/" + _id,--}}
                            url: "{{route('admin.domain.delete')}}",
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


        })

    </script>
@endpush
