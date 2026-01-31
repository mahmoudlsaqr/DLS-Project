<div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="{{ "modal_{$id}" }}">
    <div class="modal-dialog modal-dialog-centered modal-{{ $size ?? "lg" }}">
        <div class="modal-content">
            <form id="{{ "form_{$id}" }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">{{ $title }}</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-secondary ms-2" data-bs-dismiss="modal" aria-label="close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body">
                    {{ $slot }}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                    @if(isset($reset))
                        @if($reset)
                            <x-indicator id='{{ "button_reset_{$id}" }}' type="reset" class="btn btn-sm btn-danger">{{ __('Reset') }}</x-indicator>
                        @endif
                    @endif
                    <x-indicator id='{{ "button_{$id}" }}' type="button" class="btn btn-sm btn-primary">{{ __('Save') }}</x-indicator>
                </div>
            </form>
        </div>
    </div>
</div>
