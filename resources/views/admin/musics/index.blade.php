@extends('admin.layouts.app')
@section('title',trans($title))
@section('button')

        <div class="d-flex justify-content-end m-2 text-right">
            @can('admin.musics.edit')
                <a class="btn btn-warning btn-sm mr-2" href="{{route('admin.musics.updateMusics')}}?action=auto&time=1&limit=5" target="_blank">
                    <i class="fa fa-spinner"></i> {{trans('Update Link')}}
                </a>
            @endcan
            @can('admin.musics.create')
                <button class="btn btn-primary btn-sm mr-2" id="createMusics">
                    <i class="fa fa-plus"></i> {{trans('Add New')}}
                </button>
            @endcan
        </div>

@endsection

@section('content')

    @can('admin.musics')
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered" id="tableMusics" style="width: 100%">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('ID')</th>
                        <th scope="col">@lang('Image')</th>
                        <th scope="col">@lang('ID')</th>
                        <th scope="col">@lang('Title')</th>
                        <th scope="col">@lang('Tags')</th>
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
    @if (auth()->user()->hasAnyPermission(['admin.musics.create', 'admin.musics.edit']))
        <div class="modal fade bd-example-modal-xl" id="modalMusic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel">@lang('Manage Music')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <form class="actionRoute" id="formMusic"
                      enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="domain_id" id="edit_domain_id">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="text-dark"> ID YouTuBe </label> <code> | , </code>
                                <input class="form-control" name="music_ids_ytb" id="music_ids_ytb" autocomplete="off"
                                       placeholder="zxcv" value="{{old('music_id_ytb')}}" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="text-dark">Tags</label>
                                <select class="form-control" name="music_tags[]" id="music_tags" multiple="multiple"></select>

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-success" id="btnMusic">@lang('Save')</button>
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

            $(document).on('click','#createMusics', function (data){
                $('#modalMusic').modal('show');
                $('#btnMusic').val('createMusic');
                $('#music_id').val('');
                $('#formMusic').trigger("reset");
                applySelect2('#music_tags', "", '{{ route('api.getTags') }}');
            });

            const tableMusics = setupDataTable(
                '#tableMusics',
                "{{ route('admin.musics.getIndex') }}",
                [
                    {data: 'id', name: 'id'},
                    {data: 'music_thumb', name: 'music_thumb'},
                    {data: 'music_url', name: 'music_url'},
                    {data: 'music_title', name: 'music_title'},
                    {data: 'music_tags', name: 'music_tags',orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                [[ 0, 'asc' ]],
                null,
                function () {
                    $("audio").on("play", function() {
                        $("audio").not(this).each(function(index, audio) {
                            audio.pause();
                        });
                    });
                },
                function (nRow, aData) {
                    if(aData.music_expire){
                        $('td', nRow).css('background-color', 'rgba(253,0,0,0.07)');
                    }else {
                        $('td', nRow).css('background-color', 'rgba(255,255,255,0)');
                    }
                },
            );
            setupDataTableScrollTop(tableMusics,100);

            $('#formMusic').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formMusic")[0]);
                var url = "";
                var successCallback = null;

                if ($('#btnMusic').val() == 'createMusic') {
                    url = "{{ route('admin.musics.store') }}";
                }
                if ($('#btnDomain').val() == 'editDomain') {
                    url = "{{ route('admin.domain.update')}}";
                }
                successCallback = function () {
                    $('#formMusic').trigger("reset");
                    $('#modalMusic').modal('hide');
                    $('#tableMusics').DataTable().draw();
                };
                handleAjaxRequest(formData, url, successCallback);
            });


            $(document).on('click','.deleteMusic', function (data){
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
                            url: "{{route('admin.musics.delete')}}",
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

            $(document).on('click','.deleteMusicTag', function (data){
                var music_id = $(this).data("music");
                var tag_id = $(this).data("tag");
                var $element = $(this).closest('span');
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
                            url: "{{route('admin.musics.deleteTag')}}",
                            data:{
                                'music_id': music_id,
                                'tag_id': tag_id,
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

            $(document).on('click','.updateMusic', function (data){
                var music_id_ytb = $(this).data("music_id_ytb");
                var $element = $(this).closest('tr');
                var row = tableMusics.row( $element ).data();
                $.ajax({
                    type: "get",
                    url: "{{route('admin.musics.getInfo')}}",
                    data:{
                        'music_id_ytb': music_id_ytb,
                    },
                    success: function (data) {
                        row.music_url = '<audio class="playback" src='+data.music.music_url+'  controls="controls" preload="none"></audio>';
                        row.music_thumb = '<a target="_blank" href="https://www.youtube.com/watch?v='+data.music.music_id_ytb+'"><img src="'+data.music.music_thumb+'" height="55"></a>'
                        tableMusics.row($element).data(row);
                        $('td', $element).css('background-color', 'rgba(255,255,255,0)');
                        Notiflix.Notify.Success(data.success);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

        })
    </script>

@endpush
