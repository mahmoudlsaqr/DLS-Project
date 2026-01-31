<div class="image-input rounded-0 {{ $class }}" data-kt-image-input="true" style="background-image: url('{{ $placeholder }}')">
    <!--begin::Image preview wrapper-->
    <div class="image-input-wrapper rounded-0 w-100px h-125px" style="background-image: url('{{ $avatar }}')"></div>
    <!--end::Image preview wrapper-->

    <!--begin::Edit button-->
    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
           data-kt-image-input-action="change"
           data-bs-toggle="tooltip"
           data-bs-dismiss="click"
           title="{{ __('Change avatar') }}">
        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

        <!--begin::Inputs-->
        <input type="file" name="{{ $name }}" id="{{ $id }}" accept=".png, .jpg, .jpeg" />
        <input type="hidden" name="{{ $name }}_remove" />
        <!--end::Inputs-->
    </label>
    <!--end::Edit button-->

    <!--begin::Cancel button-->
    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
          data-kt-image-input-action="cancel"
          data-bs-toggle="tooltip"
          data-bs-dismiss="click"
          title="{{ __('Cancel avatar') }}">
        <i class="ki-outline ki-cross fs-3"></i>
    </span>
    <!--end::Cancel button-->

    <!--begin::Remove button-->
    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
          data-kt-image-input-action="remove"
          data-bs-toggle="tooltip"
          data-bs-dismiss="click"
          title="{{ __('Remove avatar') }}">
        <i class="ki-outline ki-cross fs-3"></i>
    </span>
    <!--end::Remove button-->
</div>
<div class="form-text">{{ __('Allowed file types') }}: png, jpg, jpeg.</div>
