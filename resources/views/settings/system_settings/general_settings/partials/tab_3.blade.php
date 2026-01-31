<div class="card-body tab-pane fade {{ str_contains($route_name, 'settings.system_settings.general_settings.tab_3') ? 'active show' : '' }}" id="{{ url('tab_3') }}" role="tabpanel">
    <div class="row">
        <ul id="list" class="d-flex flex-wrap justify-content-start align-items-center gap-3">
            @foreach([
                'role' => __('Role'),
                'user' => __('User'),
                'username' => __('Username'),
                'password' => __('Password'),
                'center_name' => __('Center name'),
                'physician' => __('Physician'),
                'supervising' => __('Supervising'),
                'designer' => __('Designer'),
                'patient_name' => __('Patient name'),
                'case_number' => __('Case number'),
                'order_number' => __('Order number'),
                'work_order_number' => __('Work order number'),
            ] as $value => $label)
                <li class="btn btn-sm btn-outline btn-outline-dark fw-bold mb-2"
                    data-kt-docs-advanced-forms="interactive" data-value="{{ '{' . $value . '}' }}">
                    {{ $label }}
                </li>
            @endforeach
        </ul>
    </div>

    <form class="form" method="POST" action="{{ route('settings.system_settings.general_settings.store') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="row justify-content-start pt-6">
            @foreach([
                'new_user_message' => __('New user message'),
                'new_order_message' => __('New order message for physician supervisor and order recipients'),
                'new_order_message_to_physician' => __('New order message to physician'),
                'order_update_message' => __('Order update message'),
                'order_approval_message' => __('Order approval message from designer'),
                'order_acceptance_message_to_physician' => __('Order acceptance message to physician'),
                'order_acceptance_message_to_designer' => __('Order acceptance message to designer'),
                'order_rejection_message' => __('Order rejection message'),
                'new_case_message' => __('New case message'),
                'case_update_message' => __('Case update message'),
                'case_acceptance_message_to_physician' => __('Case acceptance message to physician'),
                'case_acceptance_message_to_supervisor' => __('Case acceptance message to supervisor'),
                'case_rejection_message' => __('Case rejection message'),
                'new_work_order_message' => __('New work order message'),
                'work_order_dlivery_message' => __('Work order dlivery message'),
                'message_back_to_rehearsal_phase_for_delivery' => __('Message back to rehearsal phase for delivery'),
                'message_final_exit_phase_for_delivery' => __('Message final exit phase for delivery'),
            ] as $name => $label)
                <div class="col-lg-6 mb-6">
                    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
                    <textarea class="form-control output" id="{{ $name }}" name="{{ $name }}" rows="3" data-kt-autosize="true">{{ old($name) ?: settings($name) }}</textarea>
                </div>
            @endforeach

            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-sm btn-primary submit_button">
                    <span class="indicator-label">{{ __("Submit") }}</span>
                    <span class="indicator-progress">{{ __("Please wait...") }}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
