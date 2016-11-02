<section class="content-header">
    <h1>
        {{ $page_action_title }}
    </h1>
    @if (Session::has('flash_alert_notice'))
    <div class="alert {{ isset($alert_class)?$alert_class:'alert-success' }}">{{ Session::get('flash_alert_notice') }}</div>
    @endif
</section>
