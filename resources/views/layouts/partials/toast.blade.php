{{------------------------------------------------------ ||
##===>  Toastr Calling  <===##
|| ------------------------------------------------------}}

{{-- ##===>  Session Success  <===## --}}
@if (session('success'))
    <script>
        @include('layouts.partials.toastr_session_ctrl')
        toastr.success("{{ __(session('success')) }}", "{{__('success')}}");
        {{--toastr.success("I do not think that means what you think it means.", "{{ session('success') }}");--}}
    </script>
@endif

{{-- ##===>  Session Error  <===## --}}
@if (session('error'))
    <script>
        @include('layouts.partials.toastr_session_ctrl')
        toastr.error("{{ __(session('error')) }}", "{{__('error')}}");
    </script>
@endif
{{-- ##===>  Session Error  <===## --}}
@if (session('info'))
    <script>
        @include('layouts.partials.toastr_session_ctrl')
        toastr.info("{{ __(session('info')) }}", "{{__('info')}}");
    </script>
@endif
{{-- ##===>  Session Error  <===## --}}
@if (session('warning'))
    <script>
        @include('layouts.partials.toastr_session_ctrl')
        toastr.warning("{{ __(session('warning')) }}", "{{__('warning')}}");
    </script>
@endif

{{-- ##===>  Session Inactive  <===## --}}
@if (session('Inactive'))
    <script>
        @include('layouts.partials.toastr_session_ctrl')
        toastr.error("{{ __(session('Inactive')) }}", "{{__('Inactive')}}");
    </script>
@endif

{{-- ##===>  Session Any Error  <===## --}}
@if (count($errors) > 0)
    @foreach ($errors ->all() as $error)
        <script>
            @include('layouts.partials.toastr_session_ctrl')
            toastr.error("{{ __($error) }}", "{{__('error')}}");
        </script>
    @endforeach
@endif
