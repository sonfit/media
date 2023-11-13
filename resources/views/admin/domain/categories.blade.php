@extends('admin.layouts.app')
@section('title',trans($title))
@section('content')
    <div class="container-fluid">
        <div class="row mt-sm-4 justify-content-center">
            <div class="col-12 col-md-2 col-lg-2">
                @include('admin.domain.components.sidebar', ['settings' => config('generalsettings.domain'), 'suffix' => '','domain_id'=>$domain_id])
                @include('admin.domain.components.loadCategories', ['settings' => config('generalsettings.domain'), 'suffix' => '','domain_id'=>$domain_id])
            </div>
            <div class="col-12 col-md-10 col-lg-10">
                <div class="container-fluid" id="container-wrapper">
                    <div class="card mb-4 card-primary shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                            <h5 class="m-0 text-white">{{$domain->domain_name}}  -  {{$domain->domain_web}}</h5>
                            @can('admin.domain.edit')
                                <div class="d-flex text-right">
                                    <a href="javascript:void(0)" data-id="{{$domain->id}}" class="btn btn-warning btn-sm btn-rounded editDomain"><i class="fa fa-edit"></i> Edit</a>
                                &nbsp;
                                    <a href="javascript:void(0)" data-id="{{$domain->id}}" class="btn btn-success btn-sm btn-rounded createCategory"><i class="fa fa-plus"></i> Add Category</a>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered" id="tableCategories">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Image</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Read</th>
                                        <th scope="col">Tags</th>
                                        <th scope="col">Wallpapers</th>
                                        <th scope="col">Ringtones</th>
                                        <th scope="col">Musics</th>
                                        <th scope="col">@lang('Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.domain.components.modal')
    @include('admin.domain.components.modalCategory',['domain_id'=>$domain_id])
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
            const url = window.location.href;
            const parts = url.split('/');
            const urlId = parts[parts.length - 2];

            const tableLoadDomain = setupDataTable(
                '#tableLoadDomain',
                "{{ route('admin.domain.getIndex')}}",
                [
                    {data: 'id', name: 'id', visible: false},
                    {data: 'domain_web', name: 'domain_web'},
                ],
                [[ 0, 'asc' ]],
                null,
                null,
                function (row, data) {
                    if (data.id == urlId) {
                        $(row).addClass('selected');
                    }
                    $(row).on('click', function () {
                        var url = "{{ route('admin.domain.categories', ['id' => ':id']) }}";
                        url = url.replace(':id', data.id);
                        window.location.href = url;
                    });
                },
            );

            const tableCategories = setupDataTable(
                '#tableCategories',
                "{{ route('admin.domain.getDomainCategories')}}",
                [
                    {data: 'category_image', name: 'category_image'},
                    {data: 'category_name', name: 'category_name'},
                    {data: 'category_checked_ip', name: 'category_checked_ip'},
                    {data: 'category_tags', name: 'category_tags'},
                    {data: 'wallpapers_count', name: 'wallpapers_count'},
                    {data: 'ringtones_count', name: 'ringtones_count'},
                    {data: 'musics_count', name: 'musics_count'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                [[ 0, 'asc' ]],
                { domain_id : urlId },
                function () {
                    setupMagnificPopup('.image-popup-no-margins')
                }
            );

            setupDataTableScrollTop(tableCategories,130);
            setupDataTableScrollTop(tableLoadDomain,350);

            $(document).on('click','.deleteCategory', function (data){
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
                            type: "GET",
                            url: "{{route('admin.domain.deleteCategory')}}",
                            data:{
                                'id': _id
                            },
                            success: function (data) {
                                if(data.errors){
                                    Notiflix.Notify.Failure(data.errors[0]);
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
