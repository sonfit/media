@extends('admin.layouts.app')
{{--@extends('themes.minimal.layouts.app')--}}
@section('title',trans($title))
@push('style-lib')
    <link href="{{asset('assets/admin/css/dropzone.min.css')}}" rel="stylesheet">

@endpush

@section('button')
    @can('admin.wallpapers.edit')
        <div class="d-flex justify-content-end m-2 text-right">
            <a class="btn btn-warning btn-sm mr-2" href="{{route('admin.wallpapers.compareImages')}}?action=auto&time=1" target="_blank">
                <i class="fa fa-spinner"></i> {{trans('Compare')}}
            </a>
        </div>
    @endcan
@endsection
@section('content')

    @can('admin.wallpapers.create')
        <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <CODE>Square: 1300 X 1300 <===> Landscape: 2400 X 1300 <===> Portrait: 1300 X 2400</CODE>

            <div class="row ">
                <div class="col-12">
                    <div class="form-group">
                        <form id="formWallpaperUpload" class="dropzone"
                              enctype="multipart/form-data">
                            <select class="form-control" name="wallpaper_tags[]" id="wallpaper_tags" multiple="multiple"></select>
                            <div class="fallback">
                                <input  name="image" id="image" multiple="multiple">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
    @can('admin.wallpapers')
        @include('partials.wallpapers')
    @endcan



@endsection

@push('js')
    <script src="{{ asset('assets/admin/js/dropzone.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
    <script>

        Dropzone.autoDiscover = false;
        let wallpapersIndexUrl = "{{ route('admin.wallpapers.getIndex') }}";

        $(document).ready(function () {
            "use strict";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            applySelect2('#wallpaper_tags', "", '{{ route('api.getTags') }}');

            var myDropzone = new Dropzone("#formWallpaperUpload", {
                url: "{{ route('admin.wallpapers.store') }}", // URL gửi lên server
                maxFilesize: 10, // Kích thước tệp tối đa (MB)
                acceptedFiles: ".jpeg,.jpg,.png,.gif", // Các loại tệp được chấp nhận
                addRemoveLinks: true, // Hiển thị liên kết để xoá tệp
                timeout: 0, // Thời gian chờ tối đa khi tải lên (0 để không giới hạn)
                dictRemoveFile: 'Xoá', // Văn bản cho liên kết xoá tệp
                params: { // Dữ liệu form bạn muốn gửi cùng với tệp
                    _token: '{{ csrf_token() }}' // Token CSRF của Laravel (nếu cần)
                },
                uploadMultiple: false, // Cho phép multi-upload
                // parallelUploads: 25, // Số lượng tệp tải lên đồng thời
                paramName: 'wallpaper_upload'
            });
            myDropzone.on("success", function (file, response) {
                if (response.success) {
                    // Xoá tệp sau khi tải lên thành công
                    this.removeFile(file);
                    // Notiflix.Notify.Success(response.success);
                    var uploadingFiles  = this.getUploadingFiles();
                    console.log(uploadingFiles.length)
                    if (uploadingFiles.length === 0) {
                        // Nếu không còn tệp nào đang chờ tải lên, bạn có thể thực hiện hành động sau khi hoàn thành tất cả tệp
                        Notiflix.Notify.Success(response.success);
                        applySelect2('#wallpaper_tags', "", '{{ route('api.getTags') }}');
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
                var wallpaperTags = $('#wallpaper_tags').val();
                if (wallpaperTags.length === 0) {
                    // Nếu không có giá trị, hiển thị thông báo lỗi và ngăn việc tải lên
                    Notiflix.Notify.Failure('Vui lòng chọn ít nhất một thẻ wallpaper.');
                    this.removeFile(file); // Loại bỏ tệp không hợp lệ
                }
            });

        })
    </script>
    <script>

    </script>
    <script src="{{ asset('assets/admin/js/wallpaper.js')}}"></script>

@endpush
