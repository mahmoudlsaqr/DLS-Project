@if($type === "button")
    <button type="button" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __("Sign Out") }}</button>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"> @csrf </form>
@endif

@if($type === "link")
    <div class="menu-item px-5">
        <a class="menu-link px-5" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __("Sign Out") }}</a>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">  @csrf </form>
@endif
