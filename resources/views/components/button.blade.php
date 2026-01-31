<a {{ isset($href) ? 'href=' . $href : '' }} {{ $attributes->merge(['class' => 'btn btn-sm fw-bold btn-primary']) }}>
    {{ $slot }}
</a>
