<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    @if (Session::has('flash_alert_notice'))
    <div class="alert {{ isset($alert_class)?$alert_class:'alert-success' }}">{{ Session::get('flash_alert_notice') }}</div>
    @endif
</section>
<div class="row">
    @if ( $errors->count() > 0 )
    <div class="alert alert-danger">
        <ul>
            @foreach( $errors->all() as $message )</p>
            <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>