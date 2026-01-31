@extends('layouts.landing', [
    "Title" => __("Landing")
])
@section('toolbar_buttons')
    <a href="{{ route("landing.doctors-requests.create") }}" class="btn btn-sm btn-success">{{ __("Doctors requests") }}</a>
    <a href="{{ route("landing.work_orders.search_page") }}" class="btn btn-sm btn-success">{{ __("Search for work order") }}</a>
    @if (Auth::check())
        <a href="{{ route("dashboard") }}" class="btn btn-sm btn-success">{{ __("Dashboard") }}</a>
    @else
        <a href="{{ route("dashboard") }}" class="btn btn-sm btn-success">{{ __("Sign in") }}</a>
    @endif
@endsection
@section('content')

@endsection
