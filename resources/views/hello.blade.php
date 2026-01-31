@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

{{ app()->getLocale() }}<br>
{{ LaravelLocalization::getLocalizedURL("en") }}<br>
{{ LaravelLocalization::getNonLocalizedURL('/es/about') }}<br>
{{ LaravelLocalization::getCurrentLocale() }}<br>
{{ LaravelLocalization::getCurrentLocaleName() }}<br>
{{ LaravelLocalization::getCurrentLocaleNative() }}<br>
{{ LaravelLocalization::getCurrentLocaleRegional() }}<br>
{{ LaravelLocalization::getCurrentLocaleDirection() }}<br>
{{ LaravelLocalization::getCurrentLocaleScript() }}<br>

<ul>
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <li>
            <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                {{ $properties['native'] }}
            </a>
        </li>
    @endforeach
</ul>
<br>

@include("layouts.partials.logout_button", ["type" => "button"])
