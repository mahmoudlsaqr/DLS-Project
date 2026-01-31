@php
    use Illuminate\Support\Facades\Route;
    $route_name = Route::currentRouteName();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("System settings"),
    "Title" => __("General settings")
])
@section('content')
    <div class="card p-4">
        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with( $route_name, 'settings.system_settings.general_settings.tab_1') ? 'active text-primary' : null }}
                 {{ str_contains( $route_name, 'settings.system_settings.general_settings.index') ? 'active text-primary' : null }}"
                   href="{{ route('settings.system_settings.general_settings.tab_1') }}">{{ __('Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with( $route_name, 'settings.system_settings.general_settings.tab_2') ? 'active text-primary' : null }}"
                   href="{{ route('settings.system_settings.general_settings.tab_2') }}">{{ __('Notifications') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with( $route_name, 'settings.system_settings.general_settings.tab_3') ? 'active text-primary' : null }}"
                   href="{{ route('settings.system_settings.general_settings.tab_3') }}">{{ __('SMS settings') }}</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            @include('settings.system_settings.general_settings.partials.tab_1')
            @include('settings.system_settings.general_settings.partials.tab_2')
            @include('settings.system_settings.general_settings.partials.tab_3')
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(function () {
            let LoginSubmit =  $("#submit_btn");
            LoginSubmit.click(function() {
                LoginSubmit.attr('data-kt-indicator', 'on');
                $("#form_create").submit();
                LoginSubmit.prop('disabled', true);
                LoginSubmit.removeAttr('data-kt-indicator').prop('disabled', false);
            });
        });
    </script>

    <script>
        let TextAreas = document.querySelectorAll('textarea.output');
        let currentTextarea;

        function setFocus(event) { currentTextarea = event.target; }
        document.getElementById('list').addEventListener('click', function(event) {
            let target = event.target;
            if (target.tagName === 'LI') {
                if (currentTextarea) { currentTextarea.value += target.getAttribute('data-value') + ' '; }
            }
        });
        for (let i = 0; i < TextAreas.length; i++) { TextAreas[i].addEventListener('focus', setFocus); }
    </script>
@endsection
