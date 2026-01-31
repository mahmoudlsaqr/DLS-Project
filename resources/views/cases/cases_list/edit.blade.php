@php
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    $locale = app() ->getLocale();
    $User = Auth::user();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Cases"),
    "Title" => __("Edit case")
])
@section("toolbar_buttons")
    @include("layouts.partials.back_button", ["link" => route("cases.cases_list.index"), "label" => __("Cases list")])
@endsection
@section('content')
    <div class="card">
        <form id="form_update" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">
                <div class="row ">
                    <!--begin::Input group-->
                    <input value="{{ $Case["id"] }}" type="hidden" name="id">
                    <div class="col-lg-3 mb-7">
                        <label class="form-label required fw-semibold fs-7  mb-2">{{ __("Case Number") }}</label>
                        <input type="text" name="case_number" value="{{ old('case_number')? : $Case["case_number"] }}" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="{{ __("Case Number") }}"/>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-3 mb-7">
                        <label class="form-label fs-7 fw-semibold">{{ __("Physician") }}</label>
                        <select class="form-select form-select-sm" id="role_edit" data-kt-select2="true" data-placeholder="{{ __("Select option") }}"
                                data-allow-clear="false" data-kt-user-table-filter="physician" data-hide-search="false" name="physician">
                            <option></option>
                            @foreach($users as $item)
                                @if($item["role"] == "physician")
                                    <option value="{{ $item["id"] }}" @if($item["id"] == $Case["physician"]) selected @endif>{{ $item["name"] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-2 mb-7">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Patient Name") }}</label>
                        <input type="text" name="patient_name" value="{{ old('patient_name')? : $Case["patient_name"] }}" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="{{ __("Patient Name") }}"/>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-2 mb-7">
                        <label class="form-label fw-semibold fs-7 mb-2">{{ __("Gender") }}</label>
                        <select class="form-select form-select-sm" data-kt-select2="true" data-hide-search="true" data-placeholder="{{ __("Select option") }}" name="gender">
                            <option></option>
                            <option value="0" @if(0 == $Case["gender"]) selected @endif>{{ __("Female") }}</option>
                            <option value="1" @if(1 == $Case["gender"]) selected @endif>{{ __("Male") }}</option>
                        </select>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-2 mb-7">
                        <label class="form-label fw-semibold fs-7 mb-2">{{ __("Age") }}</label>
                        <input type="text" oninput="input_number(this)" value="{{ old('age')? : $Case["age"] }}" name="age" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="{{ __("Age") }}"/>
                    </div>
                    <!--end::Input group-->
                    @if($Case->status != 1)
                    <!--begin::Input group-->
                    <div class="col-lg-6 mb-7">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Note") }}</label>
                        <textarea class="form-control" rows="4" name="note" disabled readonly
                                  placeholder="{{ __("Write something ...") }}">{{ $Case["note"] }}</textarea>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-6 mb-7">
                        <label class="form-label fw-semibold fs-7 mb-2">{{ __("Other note") }}</label>
                        <textarea class="form-control" rows="4" name="case_note"
                                  placeholder="{{ __("Write something ...") }}"></textarea>
                    </div>
                    <!--end::Input group-->
                    @elseif($Case->status == 1)
                    <!--begin::Input group-->
                    <div class="col-lg-12 mb-7">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Note") }}</label>
                        <textarea class="form-control" rows="4" name="note"
                                  placeholder="{{ __("Write something ...") }}">{{ $Case["note"] }}</textarea>
                    </div>
                    <!--end::Input group-->
                    @endif

                </div>
            </div>
            <div class="card-footer">
                <div class="mb-2">
                    <button type="submit" id="submit_btn" class="btn h-35px btn-sm btn-primary btn-primary" data-kt-users-modal-action="submit">
                        <span class="indicator-label">{{ __("Submit") }}</span>
                        <span class="indicator-progress">{{ __("Please wait...") }}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>

            @if(auth()->user()->can('view_attachments'))
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        @include('layouts.partials.table_search')
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end gap-2" data-kt-user-table-toolbar="base">

                            {{--    ##===> FILTER COLUMNS <===##--}}
                            @include('layouts.partials.filter_columns_button')
                            @php
                                $columns = ['check' , '#', __('File'), __('Type') ];
                                $col_start = 1;
                            @endphp
                            @include('layouts.partials.filter_columns')

                            @if(auth()->user()->can('add_attachments'))
                                @include('layouts.partials.add_button_toggle')
                            @endif
                        </div>
                        <!--end::Toolbar-->

                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body">
                    <div class="row">
                        @include('cases.case_attachments.partials.table')
                    </div>
                </div>
                <!--end::Card body-->
            @endif
        </form>
    </div>

    @if(auth()->user()->can('view_attachments'))
        @if(count($CaseAttachment) > 0)
            @foreach($CaseAttachment as $item)
                <div class="modal bg-body fade" tabindex="-1" id="modal_video_{{ $item["id"] }}_view">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content shadow-none">
                            <!--begin::Modal header-->
                            <div class="modal-header d-flex justify-content-between align-items-center">
                                <h2 class="modal-title fs-4" id="exampleModalLabel">{{ __("View video") }}</h2>
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2 modal_{{ $item["id"] }}_cancel" data-bs-dismiss="modal" aria-label="cancel">
                                    <i class="ki-outline ki-cross fs-1"></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <!--end::Modal header-->
                            <div class="modal-body d-flex justify-content-center align-items-center">
                                <video class="vide_{{ $item["id"] }}" height="100%" width="100%" controls>
                                    <source class="video_source" src="{{ "uploads/case_attachments/{$item->Cases->case_number}/{$item['file']}" }}" type="video/mp4">
                                </video>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light modal_{{ $item["id"] }}_cancel" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="modal bg-body fade" tabindex="-1" id="modal_image_view">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content shadow-none">
                    <!--begin::Modal header-->
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h2 class="modal-title" id="exampleModalLabel">{{ __("View image") }}</h2>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2" data-bs-dismiss="modal" aria-label="cancel">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->

                    <div class="modal-body d-flex justify-content-center align-items-center w-100">
                        <div class="mw-50">
                            <img class="image_source w-100 p-4" src="" alt="image_view"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@if(auth()->user()->can('add_attachments'))
    <!--begin::Modals actions-->
    @section('modal_add_content')
        <input type="hidden" name="case_id" value="{{ $Case["id"] }}" />
        <div id="attachments_repeater">
            <div class="table-responsive">
                <table class="table gs-2 mb-0 fw-semibold">
                    <thead>
                    <tr class="fs-7 fw-bold text-start">
                        <th class="form-label required fw-semibold">{{ __('Attachments') }}</th>
                        <th class="form-label required fw-semibold">{{ __('Description') }}</th>
                        <th class="form-label required fw-semibold">{{ __('Type') }}</th>
                    </tr>
                    </thead>

                    <tbody data-repeater-list="attachments_repeater">
                    <tr data-repeater-item="" data-kt-element="item">
                        <td class="px-1">
                            <input class="form-control form-control-sm fs-7" type="file" name="file"
                                   accept=".pdf, .stl, image/*, video/*">
                        </td>
                        <td class="px-1">
                            <input class="form-control form-control-sm fs-7" type="text" name="description">
                        </td>
                        <td class="px-1">
                            <select class="form-select form-select-sm fs-7" data-kt-repeater="select2"
                                    name="type" data-dropdown-parent="#modal_add">
                                <option></option>
                                <option value="1">{{ __("Image") }}</option>
                                <option value="2">{{ __("Video") }}</option>
                                <option value="3">{{ __("Pdf") }}</option>
                                <option value="4">{{ __("Stl") }}</option>
                            </select>
                        </td>
                        <td class="px-1">
                            <a  data-repeater-delete="" class="btn h-35px btn-sm btn-icon btn-flex btn-light-danger">
                                <i class="ki-solid ki-trash fs-5"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-3">
                <button type="button" href="javascript:;" data-repeater-create class="btn btn-sm btn-light-success fs-7" id="AddButton">
                    <i class="ki-duotone ki-plus fw-bold fs-5"></i>
                    {{ __("Add row") }}
                </button>
            </div>
        </div>
    @endsection
    <!--end::Modals actions-->
@endif

@section("script")
    @include('cases.case_attachments.partials.scripts')
    @include("layouts.partials.script_form_update", [
       "Form_route" => route('cases.cases_list.update', [$Case["id"]]),
       "Redirect_route" => route('cases.cases_list.index')
   ])
    <script src="{{asset("assets/plugins/custom/formrepeater/formrepeater.bundle.js")}}"></script>
    <script>
        $(function () {

            @if(auth()->user()->can('view_attachments'))
                @if(count($CaseAttachment) > 0)
                    @php $i = 0; @endphp
                    @foreach($CaseAttachment as $item)
                        $(document).on('click', '.modal_{{ $item["id"] }}_cancel', function() {
                            var video = $('video');
                            video.get({{ $i }}).pause();
                            video.get({{ $i }}).stop();
                        });
                        @php ++$i @endphp
                    @endforeach
                @endif

                $(document).on('click', '.image_view_btn', function () {
                    let Src = $(this).data('src');
                    $('.image_source').attr('src', Src);
                    $('#modal_image_view').modal('show');
                });
            @endif

            $('#attachments_repeater').repeater({
                initEmpty: false,

                defaultValues: {
                    'text-input': 'foo'
                },

                show: function () {
                    $(this).slideDown();

                    // Re-init select2
                    $('[data-kt-repeater="select2"]').select2({
                        placeholder: '{{ __("Select option") }}'
                    });
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                ready: function(){
                    // Init select2
                    $('[data-kt-repeater="select2"]').select2({
                        placeholder: '{{ __("Select option") }}'
                    });
                }
            })
        })
    </script>

@endsection
