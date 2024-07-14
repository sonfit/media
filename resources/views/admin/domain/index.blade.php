@extends('admin.layouts.app')
@section('title',trans($title))
@section('button')
        <div class="d-flex justify-content-end m-2 text-right">
            @can('admin.domain.create')
                <button class="btn btn-primary btn-sm mr-2" id="createDomain">
                    <i class="fa fa-plus"></i> {{trans('Add New')}}
                </button>
            @endcan
            @can('admin.domain.edit')
                <button class="btn btn-secondary btn-sm mr-2" id="updateDomain">
                    <i class="fa fa-download"></i> {{trans('Update')}}
                </button>
            @endcan

        </div>
@endsection
@section('content')
    @can('admin.domain')
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered" id="tableDomain">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('SL')</th>
                        <th scope="col">@lang('Logo')</th>
                        <th scope="col">@lang('Domain')</th>
                        <th scope="col">@lang('Project AIO')</th>
                        <th scope="col">@lang('Categories')</th>
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
                    {data: 'domain_logo', name: 'domain_logo'},
                    {data: 'domain_web', name: 'domain_web'},
                    {data: 'domain_project', name: 'domain_project'},
                    {data: 'categories_count', name: 'categories_count', orderable: false},
                    // {data: 'wallpapers_count', name: 'wallpapers_count', orderable: false},
                    // {data: 'ringtones_count', name: 'ringtones_count', orderable: false},
                    // {data: 'musics_count', name: 'musics_count', orderable: false},
                    {data: 'is_publish', name: 'is_publish'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                [[ 0, 'asc' ]],
                null,
                function () {
                    setupMagnificPopup('.image-popup-no-margins')
                }
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

            $(document).on('click','.getInfoAIO', function (data){
                var id = $(this).data("id");
                var $element = $(this).closest('tr');
                var row = tableDomain.row( $element ).data();
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/domains/get-info-aio") }}/" + id,
                    success: function (data) {
                        if(data.error){
                            Notiflix.Notify.Failure(data.error);
                        }
                        if(data.success) {
                            var imagePath = '/storage/logos/'+data.domain.id+'/'+data.domain.domain_logo
                            row.domain_logo = '<a class="image-popup-no-margins" href="'+imagePath+'"><img src="' +imagePath + '" height="55"></a>';
                            row.domain_web = '<h5 class="text-dark mb-0 font-16 font-medium">'+data.domain.domain_name+'</h5><span class="text-muted font-14"><i class="fa fa-globe mr-2"></i>'+data.domain.domain_web+'</span>'
                            tableDomain.row($element).data(row);
                            Notiflix.Notify.Success(data.success);
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        })

    </script>
@endpush
