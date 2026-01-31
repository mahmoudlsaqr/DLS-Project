<script>
    $(function() {
        $('.fc-flatpickr').flatpickr({
            locale: '{{ app() ->getLocale() }}',
            weekNumbers: true,
            altInput: true,
            altFormat: "{{ (settings('date_format') != null)? settings('date_format') : "d-m-Y" }}",
            dateFormat: "Y-m-d",

        });

        $('.fc-timepickr').flatpickr({
            locale: '{{ app() ->getLocale() }}',
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });

        flatpickr('.dateGregorianBirth', {
            locale: '{{ app() ->getLocale() }}',
            weekNumbers: true,
            plugins: [
                hijriCalendarPlugin(luxon.DateTime, {
                    showHijriDates: true,
                    showHijriToggle: false,
                }),
            ],
            altInput: true,
            allowInput: true,
            altFormat: "{{ (settings('date_format') != null)? settings('date_format') : "d-m-Y" }}",
            dateFormat: "Y-m-d",
            maxDate: "today",
            minDate: "{{ $minDateGregorian }}",
        });

        flatpickr('.dateHijriBirth', {
            locale: '{{ app() ->getLocale() }}',
            weekNumbers: true,
            plugins: [
                hijriCalendarPlugin(luxon.DateTime, {
                    showHijriDates: true,
                    showHijriToggle: false,
                }),
            ],
            altInput: true,
            allowInput: true,
            altFormat: "{{ (settings('date_format') != null)? settings('date_format') : "d-m-Y" }}",
            dateFormat: "Y-m-d",
            maxDate: "{{ $maxDateHijri }}",
            minDate: "{{ $minDateHijri }}",
        });

        flatpickr('.dateGregorian', {
            locale: '{{ app() ->getLocale() }}',
            weekNumbers: true,
            plugins: [
                hijriCalendarPlugin(luxon.DateTime, {
                    showHijriDates: true,
                    showHijriToggle: false,
                }),
            ],
            altInput: true,
            allowInput: true,
            altFormat: "{{ (settings('date_format') != null)? settings('date_format') : "d-m-Y" }}",
            dateFormat: "Y-m-d",
            maxDate: "today",
        });

        flatpickr('.dateHijri', {
            locale: '{{ app() ->getLocale() }}',
            weekNumbers: true,
            plugins: [
                hijriCalendarPlugin(luxon.DateTime, {
                    showHijriDates: true,
                    showHijriToggle: false,
                }),
            ],
            altInput: true,
            allowInput: true,
            altFormat: "{{ (settings('date_format') != null)? settings('date_format') : "d-m-Y" }}",
            dateFormat: "Y-m-d",
            maxDate: "{{ $maxDateHijri }}",
        });
    });
</script>
