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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered" id="tableMusics" style="width: 100%">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">@lang('Ip Address')</th>
                                        <th scope="col">@lang('ID')</th>
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
                </div>
            </div>
        </div>
    </div>

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
            let musicsIndexUrl = `{{ route('admin.domain.getDomainMusics',['id'=>':id']) }}`;
            musicsIndexUrl = musicsIndexUrl.replace(':id', urlId);


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
                        var url = "{{ route('admin.domain.ringtones', ['id' => ':id']) }}";
                        url = url.replace(':id', data.id);
                        window.location.href = url;
                    });
                },
            );

            const tableMusics = setupDataTable(
                '#tableMusics',
                musicsIndexUrl,
                [
                    {data: 'music_thumb', name: 'music_thumb'},
                    {data: 'music_url', name: 'music_url'},
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
                }
            );
            setupDataTableScrollTop(tableLoadDomain,350);

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
                                }else {
                                    $element.fadeOut(400, function () {
                                        $(this).remove();
                                    });
                                    Notiflix.Notify.Success(data.success);
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
                var $deleteElement = $(this).closest('span');
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
                                $deleteElement.fadeOut(400, function () {
                                    $(this).remove();
                                });
                                Notiflix.Notify.Success(data.success);
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
                        row.music_thumb = '<img src="'+data.music.music_thumb+'" height="55">'
                        tableMusics.row($element).data(row);
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
