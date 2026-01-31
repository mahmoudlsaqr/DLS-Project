@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Work orders"),
    "Title" => __("Doctors requests")
])
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                @include('layouts.partials.table_search')
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end gap-2" data-kt-user-table-toolbar="base">
                    @include('layouts.partials.filter_columns_button')
                    @php
                        $columns = [
                            'check',
                             '#',
                             __("Request code"),
                             __("File number"),
                             __("Patient name"),
                             __("Doctor name"),
                             __("Number teeth"),
                             __("Color code"),
                             __("Duration delivery"),
                             __("Type case"),
                             __("Request status"),
                             __("Confirmed by"),
                             __("Actions"),
                         ];
                        $col_start = 1;
                    @endphp
                    @include('layouts.partials.filter_columns')
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
                <thead>
                <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
                    <th class="w-50px">
                        <div class="form-check form-switch d-flex justify-content-center align-items-center">
                            <input type="checkbox" class="form-check-input h-20px w-20px p-0 check_all"/>
                        </div>
                    </th>
                    <th class="w-50px">#</th>
                    <th class="w-150px">{{ __("Request code") }}</th>
                    <th class="w-100px">{{ __("File number") }}</th>
                    <th class="w-300px">{{ __("Patient name") }}</th>
                    <th class="w-300px">{{ __("Doctor name") }}</th>
                    <th class="w-150px">{{ __("Number teeth") }}</th>
                    <th class="w-100px">{{ __("Color code") }}</th>
                    <th class="w-200px">{{ __("Duration delivery") }}</th>
                    <th class="w-200px">{{ __("Type case") }}</th>
                    <th class="w-150px">{{ __("Request status") }}</th>
                    <th class="w-200px">{{ __("Confirmed by") }}</th>
                    <th class="w-100px">{{ __("Actions") }}</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="details-modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h2 class="modal-title fs-4" id="exampleModalLabel">{{ __("Request details") }}</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2" data-bs-dismiss="modal" aria-label="cancel">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body px-4">
                    <p id="text-details-content">{{ __("Loading...") }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")

    <script type="text/javascript">
        const locale = '{{ app() ->getLocale() }}';
        $(function () {

            let columns = [
                { data: 'checkbox', orderable: false, searchable: false },
                { data: 'id' },
                { data: 'main_code' },
                { data: 'file_number' },
                { data: 'patient_name' },
                { data: 'doctor_name' },
                { data: 'number_teeth' },
                { data: 'color_code' },
                { data: 'duration_delivery' },
                { data: 'type_case' },
                { data: 'request_status' },
                { data: 'confirmed_by' },
                { data: 'actions' },
            ];

            let Table = dataTable({
                url: `{{ route('work_orders.doctors-requests.data') }}`,
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                }
            }, columns);
            Table.draw();

            $('.f_search').on("input", () => Table.draw());

            @include('layouts.partials.filter_columns_script')

            $(document).on('click', '.view_details', function() {
                let id = Number($(this).data('id'));
                let route = `{{ route_ID_('work_orders.doctors-requests.get_details') }}`;
                const routeUrl = route.replace('_ID_', id);

                if (id > 0) {
                    $.ajax({
                        url: routeUrl,
                        type: 'GET',
                        success: function (data) {
                            if (data.error) {
                                Swal.fire(messages.error, data.error, "error");
                            }

                            let content = '';

                            if (data.length > 0) {
                                content += `
                                    <table class="table table-hover border table-row-bordered align-middle fs-7">
                                        <thead>
                                        <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
                                            <th class="w-25px">#</th>
                                            <th class="w-150px">{{ __("File name") }}</th>
                                            <th class="w-50px">{{ __("Size") }}</th>
                                            <th class="w-50px">{{ __("Actions") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                                        $.each(data, function (index, item) {
                                            content += `<tr class="${(item["id"] % 2 === 1) ? "table-Light" : "bg-secondary"}">`;
                                            content += `<td>${item["id"]}</td>`;
                                            content += `<td>${item["filename"]}</td>`;
                                            content += `<td>${item["size"]}</td>`;
                                            content += `<td>
                                                <a class="btn btn-sm btn-icon btn-twitter btn-download" data-id="${item["id"]}">
                                                    <i class="ki-outline ki-cloud-download fs-2"></i>
                                                </a>
                                            </td>`;
                                            content += `</tr>`;
                                        });
                                        content += `
                                    </tbody>
                                </table>
                                `;
                            } else {
                                content = '<p>{{ __('No attachments available for this request.') }}</p>';
                            }

                            $('#text-details-content').html(content);
                            $('#details-modal').modal('show');
                        },
                        error: function (xhr) {
                            console.log(xhr)
                            $('#text-details-content').html('An error occurred while fetching the details.');
                            $('#details-modal').modal('show');
                        }
                    });
                }
            });

            $(document).on('click', '.btn-download', function () {
                let id = Number($(this).data('id'));
                if (id > 0) {
                    let route = `{{ route('work_orders.doctors-requests.attachments.download') }}`;

                    $.ajax({
                        url: route,
                        method: 'GET',
                        data: { id: id, check_only: 1 }, // Check only flag
                        success: function (res) {
                            if (res.valid) {
                                // Start actual download
                                window.open(`${route}?id=${id}`, '_blank');
                            } else {
                                Swal.fire("{{ __('error') }}", res.error || 'File not available.', 'error');
                            }
                        },
                        error: function (xhr) {
                            Swal.fire("{{ __('error') }}", xhr.responseJSON?.error || 'Something went wrong.', 'error');
                        }
                    });
                }
            });


        });
    </script>

    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')
@endsection

