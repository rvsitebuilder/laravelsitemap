@extends('rvsitebuilder/laravelsitemap::admin.layouts.app')

@section('content')
<h2>Generate sitemap</h2>

<div class="rv-admin uk-form" >
  <div class="uk-form">
    <label class="uk-form-label"><span>Site URL:</span></label>
    <input type="text" id="" class="uk-form-width-large uk-form-blank" value="<?php echo $url; ?>" readonly="true" />
    <div style="margin-top:5px">
      <button type="button" id="sitemapSetup" class="uk-button uk-button-primary">Create sitemap</button>
      <span id="sitemapsetupWait" class="uk-hidden"><i class="uk-icon-spinner uk-icon-spin"></i> Wait</span>
      <span id="sitemapsetupSuccess" class="uk-hidden"><i class="uk-icon uk-icon-check uk-text-success"> <span id="spanSuccessMsg">-</span></i></span>
      <span id="sitemapsetupError" class="uk-hidden"><i class="uk-icon uk-icon-info-circle uk-text-danger"> Error</i></span>
    </div>
  </div>

  <div class="uk-form" style="margin-top:15px">
    Last created file : <span id="spanLastCreatedFile">{!! ($last_created!='' ? $last_created : '-') !!}</span>
  </div>
</div>
@endsection

@push('package-scripts')
  {!! script(mix('js/admin/app.js', 'vendor/rvsitebuilder/laravelsitemap')) !!}
@endpush
