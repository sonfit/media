<div class="modal fade bd-example-modal-xl" id="modalDomain" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="myModalLabel">@lang('Manage Domain')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <form class="actionRoute" id="formDomain"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="domain_id" id="edit_domain_id">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="text-dark"> Domain Url</label>
                            <input class="form-control" name="domain_web" id="domain_web" autocomplete="off"
                                   placeholder="zxcv.com" value="{{old('domain_web')}}" required>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="text-dark">Domain Name</label>
                            <input class="form-control" name="domain_name" id="domain_name" autocomplete="off"
                                   placeholder="Tên domain" value="{{old('domain_name')}}" required>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="text-dark">Project AIO</label>
                            <input class="form-control" name="domain_project" id="domain_project" autocomplete="off"
                                   placeholder="Tên Project bên AIO" value="{{old('domain_project')}}" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="text-dark">@lang('Status')</label>
                            <div class="custom-switch-btn w-md-80">
                                <input type='hidden' value='1' name='domain_status'>
                                <input type="checkbox" name="domain_status" class="custom-switch-checkbox"
                                       id="domain_status">
                                <label class="custom-switch-checkbox-label" for="domain_status">
                                    <span class="custom-switch-checkbox-inner"></span>
                                    <span class="custom-switch-checkbox-switch"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="text-dark">@lang('Status ADS')</label>
                            <div class="custom-switch-btn w-md-80">
                                <input type='hidden' value='1' name='isAds_status'>
                                <input type="checkbox" name="isAds_status" class="custom-switch-checkbox"
                                       id="isAds_status">
                                <label class="custom-switch-checkbox-label" for="isAds_status">
                                    <span class="custom-switch-checkbox-inner"></span>
                                    <span class="custom-switch-checkbox-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-success" id="btnDomain">@lang('Save')</button>
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

            $(document).on('click','#createDomain', function (data){
                $('#modalDomain').modal('show');
                $('#btnDomain').val('createDomain');
                $('#domain_id').val('');
                $('#formDomain').trigger("reset");
            });

            $(document).on('click','.editDomain', function (data){
                var id = $(this).data("id");
                $('#modalDomain').modal('show');
                $('#btnDomain').val('editDomain');
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/domains/edit") }}/" + id,
                    success: function (data) {
                        $('#edit_domain_id').val(data.id);
                        $('#domain_web').val(data.domain_web);
                        $('#domain_name').val(data.domain_name);
                        $('#domain_project').val(data.domain_project);

                        $("#domain_status").prop("checked", data.is_publish !== 1);
                        $("#isAds_status").prop("checked", data.is_ads !== 1);

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $('#formDomain').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#formDomain")[0]);
                var url = "";
                var successCallback = null;

                if ($('#btnDomain').val() == 'createDomain') {
                    url = "{{ route('admin.domain.store') }}";
                }
                if ($('#btnDomain').val() == 'editDomain') {
                    url = "{{ route('admin.domain.update')}}";
                }
                successCallback = function () {
                    $('#formDomain').trigger("reset");
                    $('#modalDomain').modal('hide');
                    $('#tableDomain').DataTable().draw();
                    $('#tableLoadDomain').DataTable().draw();
                };
                handleAjaxRequest(formData, url, successCallback);
            });

        })

    </script>
@endpush
