
<!--begin::Modal - Add task-->
<div class="modal fade" id="modal_add" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h2 class="modal-title fs-4" id="exampleModalLabel">{{ __("Add") }}</h2>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2" data-bs-dismiss="modal" aria-label="cancel">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-4">
                <!--begin::Form-->
                <form id="modal_form_add" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <!--begin::Scroll-->
                    <div class="scroll-y px-4 row" id="modal_add_scroll" data-kt-scroll="true"
                         data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_add_header"
                         data-kt-scroll-wrappers="#modal_add_scroll" data-kt-scroll-offset="400px">
                        @yield("modal_add_content")
                    </div>
                    <!--begin::Actions-->
                    <div class="text-center pt-10">
                        @include("layouts.partials.modal_add_buttons")
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
<!--end::Modal - Add task-->
