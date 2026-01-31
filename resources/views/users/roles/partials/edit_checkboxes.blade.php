@foreach($Permissions as $permission)
    @if($permission ->module == $module)
        @if(Str::startsWith($permission["name"], 'menu'))
            <div class="d-flex justify-content-start align-items-center gap-3 col-md-4 col-lg-3 my-2">
                <input class="form-check-input h-15px w-15px" type="checkbox"
                       name="permission[{{ $permission ->name }}]" id="{{ $module }}" value="{{ $permission->name }}"
                       {{ in_array($permission ->id, $RolePermissions) ? 'checked' : '' }}/>
                <label class="form-label fw-semibold fs-7 m-0" for="{{ $permission ->name }}">
                    {{ __("Check all") }}
                </label>
            </div>
        @else
            <div class="d-flex justify-content-start align-items-center gap-3 col-md-4 col-lg-3 my-2">
                <input class="form-check-input {{$module}} {{ $permission ->name }} h-15px w-15px" type="checkbox"
                       name="permission[{{ $permission ->name }}]" id="{{ $permission ->name }}" value="{{ $permission->name }}"
                       {{ in_array($permission ->id, $RolePermissions) ? 'checked' : '' }} />
                <label class="form-label fw-semibold fs-7 m-0" for="{{ $permission ->name }}">
                    {{ __(ucfirst(Str::before($permission ->name, '_'))) }}
                </label>
            </div>
        @endif
    @endif
@endforeach
