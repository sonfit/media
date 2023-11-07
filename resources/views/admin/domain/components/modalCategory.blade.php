<div class="modal fade bd-example-modal-xl" id="modalCategory" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header modal-colored-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <form class="actionRoute" id="formCategory"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="domain_id" id="domain_id" value="{{$domain_id}}">
                    <input type="hidden" name="category_id" id="category_id">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="image-input">
                                <label for="image-upload" id="image-label"><i class="fas fa-upload"></i></label>
                                <input type="file" name="image" placeholder="@lang('Choose image')" id="image">
                                <img id="image_preview_container" class="preview-image">
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="text-dark"> Category name</label>
                            <input class="form-control" name="category_name" id="category_name" autocomplete="off"
                                   value="{{old('category_name')}}" required>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="text-dark">Select Tags </label>
                            <select class="form-control" name="category_tags[]" id="category_tags" multiple></select>
                        </div>


                        <div class="form-group col-md-4">
                            <label class="text-dark">@lang('Status')</label>
                            <div class="custom-switch-btn w-md-80">
                                <input type='hidden' value='1' name='category_status'>
                                <input type="checkbox" name="category_status" class="custom-switch-checkbox"
                                       id="category_status">
                                <label class="custom-switch-checkbox-label" for="category_status">
                                    <span class="custom-switch-checkbox-inner"></span>
                                    <span class="custom-switch-checkbox-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-success" id="btnCategory">@lang('Save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('extra-script')
    <script>
        $(document).ready(function () {
            "use strict";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#image').change(function(){
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            applySelect2('#category_tags', "", '{{ route('api.getTags') }}');

            $(document).on('click','.createCategory', function (data){
                $('#modalCategory').modal('show');
                $('#btnCategory').val('createCategory');
                $('#formCategory').trigger("reset");
                applySelect2('#category_tags', "", '{{ route('api.getTags') }}');
            });

            $(document).on('click','.editCategory', function (data){
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ route('admin.domain.editCategory') }}",
                    data: {
                        'id':id
                    },
                    success: function (data) {
                        if(data.errors){
                            Notiflix.Notify.Failure(data.errors[0]);
                        }else{
                            $('#modalCategory').modal('show');
                            $('#btnCategory').val('editCategory');
                            $('#category_id').val(data.id);
                            $('#category_name').val(data.category_name);

                            applySelect2('#category_tags', "", '{{route('api.getTags')}}',data.tags);
                            $("#category_status").prop("checked", data.category_checked_ip !== 1);
                            var imagePath = data.category_image !== ''
                                ? '/storage/domains/' + data.domain_id + '/categories/' + data.category_image
                                : '/storage/defaultCate.png';
                            $('#image_preview_container').attr('src', imagePath);
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            });

            $('#formCategory').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formCategory")[0]);
                var url = "";
                var successCallback = null;
                if ($('#btnCategory').val() == 'createCategory') {
                    url = "{{ route('admin.domain.storeCategory') }}";
                }
                if ($('#btnCategory').val() == 'editCategory') {
                    url = "{{ route('admin.domain.updateCategory')}}";
                }
                successCallback = function () {
                    $('#formCategory').trigger("reset");
                    $('#modalCategory').modal('hide');
                    $('#tableCategories').DataTable().draw();
                };
                handleAjaxRequest(formData, url, successCallback);
            });
        })

    </script>
@endpush
