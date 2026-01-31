<label for="{{ $for }}" class="form-label mb-2 @if(isset($description)) m-0 @endif {{ $required ? 'required' : '' }} {{ $class }}">
    {{ $label }}
</label>
@if(!empty($description))
    <span data-bs-toggle="tooltip" title="{{ $description }}">
        <i class="ki-duotone ki-information text-dark fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
    </span>
@endif
