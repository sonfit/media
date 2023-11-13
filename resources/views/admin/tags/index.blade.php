@extends('admin.layouts.app')
@section('title',trans($title))
@section('button')
    @can('admin.tags.create')
        <div class="d-flex justify-content-end m-2 text-right">
            <button class="btn btn-primary btn-sm mr-2" id="createTag">
                <i class="fa fa-plus"></i> {{trans('Add New')}}
            </button>
        </div>
    @endcan
@endsection

@section('content')
    @can('admin.tags')
        <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" id="tableTags">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">@lang('Tag name')</th>
                            <th scope="col">@lang('Wallpapers')</th>
                            <th scope="col">@lang('Ringtones')</th>
                            <th scope="col">@lang('Musics')</th>
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
    @if (auth()->user()->hasAnyPermission(['admin.tags.create', 'admin.tags.edit']))
        <div class="modal fade" id="modalTag" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="tagModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <form class="actionRoute" id="formTag"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="tag_id" id="tag_id">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="text-dark"> {{trans('Tag Name')}} :</label>
                                <input class="form-control " name="tag_name" id="tag_name"
                                       placeholder="{{trans('Tag Name')}}" value="{{old('tag_name')}}" required>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success" id="btnTag">@lang('Save')</button>
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

            const tableTags = setupDataTable(
                '#tableTags',
                "{{ route('admin.tags.getIndex')}}",
                [
                    {data: 'tag_name', name: 'tag_name'},
                    {data: 'wallpapers_count', name: 'wallpapers_count'},
                    {data: 'ringtones_count', name: 'ringtones_count'},
                    {data: 'musics_count', name: 'musics_count'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                [[ 0, 'asc' ]]
            );
            setupDataTableScrollTop(tableTags,100);

            $(document).on('click','#createTag', function (data){
                $('#modalTag').modal('show');
                $('#btnTag').val('createTag');
                $('#tag_id').val('');
                $('#formTag').trigger("reset");
            });

            $(document).on('click','.editTag', function (data){
                var id = $(this).data("id");


                $.ajax({
                    type: "get",
                    url: "{{ route('admin.tags.edit') }}",
                    data:{
                      'id':id
                    },
                    success: function (data) {
                        if(data.error){
                            Notiflix.Notify.Failure(data.error);
                        }else {
                            $('#modalTag').modal('show');
                            $('#btnTag').val('editTag');
                            $('#tag_id').val(data.id);
                            $('#tag_name').val(data.tag_name);
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            });

            $(document).on('click','.deleteTag', function (data){
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
                            url: "{{route('admin.tags.delete')}}",
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
                                    });                                }
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });

            $('#formTag').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formTag")[0]);
                var url = "";
                var successCallback = null;


                if ($('#btnTag').val() == 'createTag') {
                    url = "{{ route('admin.tags.store') }}";
                }
                if ($('#btnTag').val() == 'editTag') {
                    url = "{{ route('admin.tags.update')}}";
                }
                successCallback = function () {
                    $('#formTag').trigger("reset");
                    $('#modalTag').modal('hide');
                    $('#tableTags').DataTable().draw();
                };
                handleAjaxRequest(formData, url, successCallback);
            });


        })

    </script>
@endpush
