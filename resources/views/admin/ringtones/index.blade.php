@extends('admin.layouts.app')
{{--@extends('themes.minimal.layouts.app')--}}
@section('title',trans($title))
@push('style-lib')
    <link href="{{asset('assets/admin/css/dropzone.min.css')}}" rel="stylesheet">
@endpush
@section('content')

    @can('admin.ringtones.create')
        <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
            <div class="card-body">
                <CODE>Upload nhạc chuông theo định dạng MP3</CODE>
                <div class="row ">
                    <div class="col-12">
                        <div class="form-group">
                            <form id="formRingtonesUpload" class="dropzone"
                                  enctype="multipart/form-data">
                                <select class="form-control" name="ringtones_tags[]" id="ringtones_tags" multiple="multiple"></select>
                                <div class="fallback">
                                    <input  name="ringtones" id="ringtones" multiple="multiple">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('admin.ringtones')
        <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered" id="tableRingtones" style="width: 100%">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('Ringtone')</th>
                        <th scope="col">@lang('Name')</th>
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
@endsection

@push('js')
    <script src="{{ asset('assets/admin/js/dropzone.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
    <script>

        Dropzone.autoDiscover = false;
        let ringtonesIndexUrl = "{{ route('admin.ringtones.getIndex') }}";

        $(document).ready(function () {
            "use strict";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            applySelect2('#ringtones_tags', "", '{{ route('api.getTags') }}');

            const tableRingtones = setupDataTable(
                '#tableRingtones',
                ringtonesIndexUrl,
                [
                    {data: 'ringtone_file', name: 'ringtone_file'},
                    {data: 'ringtone_name', name: 'ringtone_name'},
                    {data: 'ringtone_tags', name: 'ringtone_tags',orderable: false},
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
            setupDataTableScrollTop(tableRingtones,100);

            var myDropzone = new Dropzone("#formRingtonesUpload", {
                url: "{{ route('admin.ringtones.store') }}", // URL gửi lên server
                maxFilesize: 10, // Kích thước tệp tối đa (MB)
                acceptedFiles: ".mp3", // Các loại tệp được chấp nhận
                addRemoveLinks: true, // Hiển thị liên kết để xoá tệp
                timeout: 0, // Thời gian chờ tối đa khi tải lên (0 để không giới hạn)
                dictRemoveFile: 'Xoá', // Văn bản cho liên kết xoá tệp
                params: { // Dữ liệu form bạn muốn gửi cùng với tệp
                    _token: '{{ csrf_token() }}' // Token CSRF của Laravel (nếu cần)
                },
                uploadMultiple: false, // Cho phép multi-upload
                // parallelUploads: 25, // Số lượng tệp tải lên đồng thời
                paramName: 'ringtones_upload'
            });
            myDropzone.on("success", function (file, response) {
                if (response.success) {
                    // Xoá tệp sau khi tải lên thành công
                    this.removeFile(file);
                    // Notiflix.Notify.Success(response.success);
                    var uploadingFiles  = this.getUploadingFiles();
                    if (uploadingFiles.length === 0) {
                        // Nếu không còn tệp nào đang chờ tải lên, bạn có thể thực hiện hành động sau khi hoàn thành tất cả tệp
                        Notiflix.Notify.Success(response.success);
                        applySelect2('#ringtones_tags', "", '{{ route('api.getTags') }}');
                        tableRingtones.draw();
                    }
                } else {
                    Notiflix.Notify.Failure(response.error);
                }
            });
            myDropzone.on("error", function (file, errorMessage) {
                // Xử lý lỗi và hiển thị thông tin lỗi trên tệp
                file.previewElement.classList.add('dz-error');
                var errorElement = document.createElement('div');
                errorElement.className = 'dz-error-message';
                errorElement.innerHTML = errorMessage;
                file.previewElement.appendChild(errorElement);
            });
            myDropzone.on("addedfile", function (file) {
                // Kiểm tra giá trị của trường wallpaper_tags
                var wallpaperTags = $('#ringtones_tags').val();
                if (wallpaperTags.length === 0) {
                    // Nếu không có giá trị, hiển thị thông báo lỗi và ngăn việc tải lên
                    Notiflix.Notify.Failure('Vui lòng chọn ít nhất một thẻ ringtone.');
                    this.removeFile(file); // Loại bỏ tệp không hợp lệ
                }
            });

            $(document).on('click','.deleteRingtone', function (data){
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
                            url: "{{route('admin.ringtones.delete')}}",
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

            $(document).on('click','.deleteRingtoneTag', function (data){
                var ringtone_id = $(this).data("ringtone");
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
                            url: "{{route('admin.ringtones.deleteTag')}}",
                            data:{
                                'ringtone_id': ringtone_id,
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


        })
    </script>
{{--    <script src="{{ asset('assets/admin/js/wallpaper.js')}}"></script>--}}

@endpush
