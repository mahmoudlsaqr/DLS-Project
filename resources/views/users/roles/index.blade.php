@extends('layouts.master', [
    "Title" => __("Permissions & roles"),
    "breadcrumb_main" => __("Users")
])
@section("toolbar_buttons")
    @if(auth()->user()->can('create_roles'))
    @include("layouts.partials.back_button", ["link" => route("roles.create"), "label" => __("Create role")])
    @endif
@endsection
@section('content')
    <!--begin::Row-->
    <div class="row g-2">
        @foreach ($roles as $role)
            @if($role ->name != "owner")
                <!--begin::Col-->
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <!--begin::Card-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>{{ ucwords($role["name"]) }}</h3>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card footer-->
                        <div class="card-footer d-flex justify-content-start align-items-center flex-wrap pt-0">
                            @if(auth()->user()->can('update_roles'))
                            <a href="{{ route('roles.edit', [$role->id]) }}" class="btn btn-sm btn-secondary btn-active-success my-1 me-2">{{ __("Edit") }}</a>
                            @endif
                            @if ($role->default != 'true')
                                @if(auth()->user()->can('remove_roles'))
                                <form action="{{ route('roles.destroy', [$role ->id]) }}" id="form_destroy" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-name="{{ $role ->name }}" class="btn btn-sm btn-secondary btn-active-danger my-1 me-2 delete_role">{{ __('Delete') }}</button>
                                </form>
                                @endif
                            @else
                                <button type="button" disabled class="btn btn-sm btn-secondary btn-active-success my-1 me-2">{{ __("Delete") }}</button>
                            @endif
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
            @else
                @role("owner|admin")
                <!--begin::Col-->
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <!--begin::Card-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>{{ ucwords($role["name"]) }}</h3>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card footer-->
                        <div class="card-footer d-flex justify-content-start align-items-center flex-wrap pt-0">
                            @if(auth()->user()->can('update_roles'))
                                <a href="{{ route('roles.edit', [$role->id]) }}" class="btn btn-sm btn-secondary btn-active-success my-1 me-2">{{ __("Edit") }}</a>
                            @endif
                            @if ($role->default != 'true')
                                @if(auth()->user()->can('remove_roles'))
                                    <form action="{{ route('roles.destroy', [$role ->id]) }}" id="form_destroy" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" data-name="{{ $role ->name }}" class="btn btn-sm btn-secondary btn-active-danger my-1 me-2 delete_role">{{ __('Delete') }}</button>
                                    </form>
                                @endif
                            @else
                                <button type="button" disabled class="btn btn-sm btn-secondary btn-active-success my-1 me-2">{{ __("Delete") }}</button>
                            @endif
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                @endrole
            @endif
        @endforeach
    </div>
    <!--end::Row-->

@endsection

@section("script")
    @if(auth()->user()->can('remove_roles'))
    <script>
        $('body').on('click', '.delete_role', function (e) {
            e.preventDefault();
            let form = $(this).closest('#form_destroy');
            Swal.fire({
                title: "{{__('Are you sure to delete')}} " + e.currentTarget.getAttribute('data-name') + " {{__('?')}}",
                text: "{{__("You will not be able to undo this")}}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonText: "{{__('Cancel')}}",
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, delete it')}}",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
    @endif
@endsection

