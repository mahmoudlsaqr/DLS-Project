const loadingEl = document.createElement("div");
document.body.prepend(loadingEl);
loadingEl.classList.add("page-loader");
loadingEl.classList.add("flex-column");
loadingEl.classList.add("bg-dark");
loadingEl.classList.add("bg-opacity-25");
loadingEl.innerHTML = `<span class="spinner-border text-primary" role="status"></span>`;
@if(isset($loading))
    @if($loading == 'true')
    KTApp.showPageLoading();
    @endif
@endif

setTimeout(function() {
    {{ isset($draw_table) ? $draw_table : null }};
    @if(isset($loading))
        @if($loading == 'true')
        KTApp.hidePageLoading();
        loadingEl.remove();
        @endif
    @endif
}, 500);
