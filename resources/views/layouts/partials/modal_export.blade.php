<!--begin::Modal - Adjust Balance-->
<div class="modal fade" id="modal_export" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h2 class="modal-title fs-4" id="exampleModalLabel">{{__("Export")}}</h2>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-2 my-7">
                <!--begin::Form-->
                <form id="modal_export_form" class="form" action="#">
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="fs-6 fw-semibold form-label mb-2">{{ __("Roles") }}</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select name="role" data-control="select2" data-placeholder="{{ __("Select option") }}" data-hide-search="true"
                                class="form-select fw-bold">
                            <option></option>
                            <option value="Administrator">Administrator</option>
                            <option value="Analyst">Analyst</option>
                            <option value="Developer">Developer</option>
                            <option value="Support">Support</option>
                            <option value="Trial">Trial</option>
                        </select>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="required fs-6 fw-semibold form-label mb-2">{{ __("Export Format") }}</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select name="format" data-control="select2" data-placeholder="{{ __("Select option") }}" data-hide-search="true"
                                class="form-select fw-bold">
                            <option></option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                            <option value="cvs">CVS</option>
                            <option value="zip">ZIP</option>
                        </select>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close">{{ __("Discard") }}</button>
                        <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label">{{ __("Submit") }}</span>
                            <span class="indicator-progress">{{ __("Please wait...") }}
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - New Card-->
