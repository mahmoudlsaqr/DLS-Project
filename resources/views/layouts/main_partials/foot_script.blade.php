@php
    $locale = app()->getLocale();
@endphp

<!--begin::Javascript-->
<script>let hostUrl = "assets/";</script>

<!--begin::Theme mode setup on page load-->
<script>
    let defaultThemeMode = "light";
    let themeMode;
    if ( document.documentElement ) {
        if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
            themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); }
        else {
            if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); }
            else { themeMode = defaultThemeMode; }
        }
        if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; }
        document.documentElement.setAttribute("data-bs-theme", themeMode); }
</script>
<!--end::Theme mode setup on page load-->

@if (Auth::check())
<script>
    let countdown = 3600 * 6; // 6 hours in seconds

    const interval = setInterval(() => {
        if (countdown <= 0) {
            clearInterval(interval);
            fetch('{{ route('logout') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
            })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/login';
                    } else {
                        alert('Error occurred while logging out');
                    }
                })
                .catch(() => alert('Network error while logging out'));
        } else {
            const hours = Math.floor(countdown / 3600);
            const minutes = Math.floor((countdown % 3600) / 60);
            const seconds = countdown % 60;
            document.getElementById('countdown-timer').innerText = `${hours}:${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            countdown--;
        }

        if (countdown === 60) {
            alert('Session will end soon!');
        }
    }, 1000);

</script>
@endif

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script type="text/javascript" src="{{ url('https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>

@include('layouts.partials.toast') {{-- Toasts notification --}}

<script type="text/javascript" src="{{ asset("assets/js/scripts.bundle.js") }}"></script>
<script type="text/javascript" src="{{ asset("assets/plugins/custom/datatables/datatables.bundle.js") }}"></script>
<script type="text/javascript" src="{{ asset("assets/plugins/custom/fslightbox/fslightbox.bundle.js") }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/custom.js') }}"></script>

<script>

    document.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            const target = event.target;
            if (target.tagName !== "TEXTAREA" && target.type !== "submit" && target.tagName !== "BUTTON") {
                event.preventDefault();
            }
        }
    });

    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function () {
            const submitButton = this.querySelector("[type='submit']");
            if (submitButton) submitButton.disabled = true;
        });
    }

    $(".submit_button").click(function(e) {
        e.preventDefault();
        const $submitButton = $(this);
        const $form = $submitButton.closest('form');

        $submitButton.attr('data-kt-indicator', 'on').prop('disabled', true);
        $form.submit();
        $form.on('submit', function() { $submitButton.removeAttr('data-kt-indicator').prop('disabled', false); });
    });

    function SelectGetData(ele, route, isMulti, value) {
            ele.select2({
                placeholder: '{{ __("Select option") }}',
                allowClear: false,
                multiple: isMulti,
                ajax: {
                    url: route,
                    dataType: 'json',
                    delay: 0,
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            }).val(value).trigger("change");
    }

    $('.fc-flatpickr').flatpickr({
        locale: '{{ app() ->getLocale() }}',
        weekNumbers: true,
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Y-m-d",

    });

    function dataTable(ajax, columns, id = 'data-table') {
        return $(`#${id}`).DataTable({
            language: {url: `{{ "/assets/plugins/custom/datatables/{$locale}.json" }}`},
            processing: true,
            serverSide: true,
            responsive: false,
            deferRender: true,
            stateSave: false,
            order: [[1, "asc"]],
            pageLength: 50,
            lengthChange: false,
            deferLoading: 51,
            ajax: ajax,
            columns: columns,
        });
    }

    $(document).ready(function () {

        let datepickerArabic = {
            weekdays: {
                shorthand: ["أحد", "اثن", "ثلا", "أرب", "خمس", "جمع", "سبت"],
                longhand: [
                    "الأحد",
                    "الإثنين",
                    "الثلاثاء",
                    "الأربعاء",
                    "الخميس",
                    "الجمعة",
                    "السبت"
                ]
            },
            months: {
                shorthand: [
                    "ينا", "فبر", "مار", "أبر", "ماي", "يون",
                    "يول", "أغس", "سبت", "أكت", "نوف", "ديس"
                ],
                longhand: [
                    "يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو",
                    "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"
                ]
            },
            firstDayOfWeek: 6,
            rangeSeparator: " إلى ",
            scrollTitle: "قم بالتمرير للزيادة",
            toggleTitle: "اضغط للتبديل",
            time_24hr: true
        };

        $(".datepicker_date").flatpickr({
            locale: @if($locale == 'ar') datepickerArabic @else 'en' @endif,
            altInput: true,
            altFormat: `{{ config('general-data.date-format') ?? "d/m/Y" }}`,
            dateFormat: "Y/m/d",
            weekNumbers: true,
            allowInput: true,
            // minDate: "today"
        });

        $(".datepicker_date_range").flatpickr({
            altFormat: `{{ config('general-data.date-format') ?? "d/m/Y" }}`,
            altInput: true,
            dateFormat: "Y/m/d",
            weekNumbers: true,
            allowInput: true,
            locale: @if($locale == 'ar') datepickerArabic @else 'en' @endif,
            mode: "range"
        });

        $(".datepicker_time").flatpickr({
            locale: @if($locale == 'ar') datepickerArabic @else 'en' @endif,
            enableTime: true,
            noCalendar: true,
            allowInput: true,
            dateFormat: "H:i",
            time_24hr: false
        });
    });

</script>

@if (Auth::check())
    <script>
        $(document).ready(function () {
            let isFetchingNotifications = false;
            function fetchNotificationsSafely() {
                if (isFetchingNotifications) return;

                isFetchingNotifications = true;

                fetch("{{ route('work_orders.late_work_orders.latest') }}")
                    .then(response => response.json())
                    .then(data => {
                        const count = data.count;
                        document.querySelector('#notification-count')?.remove();

                        if (count > 0) {
                            const badge = document.createElement('span');
                            badge.id = 'notification-count';
                            badge.className = 'position-absolute top-0 end-0 translate-middle badge badge-danger badge-circle fs-9';
                            badge.innerText = count;
                            document.getElementById('kt_menu_item_wow')?.appendChild(badge);
                        }

                        const list = document.querySelector('#logs-list');
                        if (list) {
                            list.innerHTML = '';
                            data.notifications.forEach(n => {
                                list.innerHTML += `
                                    <div class="d-flex flex-stack p-4 mb-2 bg-light-warning">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-35px me-4">
                                                <span class="symbol-label bg-light">
                                                    <i class="ki-duotone ki-notification-on fs-2 text-warning">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                </span>
                                            </div>
                                            <div class="mb-0 me-2">
                                                <a href="/work_orders/work_orders_management/show/${n.order}" data-id="${n.id}" class="fs-7 text-gray-800 text-hover-primary fw-bold mark-as-read cursor-pointer">${n.title}</a>
                                                <div class="text-gray-500 fs-8">{{ __("By") }}: ${n.user}</div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });

                            if (data.notifications.length === 0) {
                                list.innerHTML = `<div class="text-center text-muted">{{ __("No new logs") }}</div>`;
                            }
                        }
                    })
                    .catch(console.error)
                    .finally(() => {
                        isFetchingNotifications = false;
                        setTimeout(fetchNotificationsSafely, 15000);
                    });
            }

            fetchNotificationsSafely();
        });
    </script>
@endif

@yield("script")
