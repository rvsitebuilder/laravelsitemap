@extends('rvsitebuilder/laravelsitemap::admin.layouts.app')

@section('content')
<h2>Sitemap setting</h2>

<div class="rv-admin uk-form" >

<form id="frmSitemapSetting" class="uk-form uk-form-horizontal">
  {{ csrf_field() }}
  <div class="uk-form-row">
    <label class="uk-form-label" >COOKIES:</label>
    <div class="uk-form-controls">
      <div class="uk-grid">
        <div class="uk-width-4-10">
          <input type="checkbox" name="setting_cookies" id="setting_cookies" @if(isset($setting_data['laravelsitemap_COOKIES']) && $setting_data['laravelsitemap_COOKIES']==1) checked @endif>
        </div>
        <div class="uk-width-6-10">
          <span class="uk-h6">
          Whether or not cookies are used in a request.
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="uk-form-row">
    <label class="uk-form-label" >CONNECT_TIMEOUT:</label>
    <div class="uk-form-controls">
      <div class="uk-grid">
        <div class="uk-width-4-10">
          <input type="number" name="setting_connect_timeout" id="setting_connect_timeout" value="@isset($setting_data['laravelsitemap_CONNECT_TIMEOUT']) {{$setting_data['laravelsitemap_CONNECT_TIMEOUT']}} @endisset " class="uk-form-width-medium">
        </div>
        <div class="uk-width-6-10">
          <span class="uk-h6">
          The number of seconds to wait while trying to connect to a server.<br>
          Use 0 to wait indefinitely.
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="uk-form-row">
    <label class="uk-form-label" >TIMEOUT:</label>
    <div class="uk-form-controls">
      <div class="uk-grid">
        <div class="uk-width-4-10">
          <input type="number" name="setting_timeout" id="setting_timeout" value="@isset($setting_data['laravelsitemap_TIMEOUT']) {{$setting_data['laravelsitemap_TIMEOUT']}} @endisset " class="uk-form-width-medium">
        </div>
        <div class="uk-width-6-10">
          <span class="uk-h6">
          The timeout of the request in seconds. Use 0 to wait indefinitely.
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="uk-form-row">
    <label class="uk-form-label" >ALLOW_REDIRECTS:</label>
    <div class="uk-form-controls">
      <div class="uk-grid">
        <div class="uk-width-4-10">
          <input type="checkbox" name="setting_allow_redirects" id="setting_allow_redirects" @if(isset($setting_data['laravelsitemap_ALLOW_REDIRECTS']) && $setting_data['laravelsitemap_ALLOW_REDIRECTS']==1) checked @endif>
        </div>
        <div class="uk-width-6-10">
          <span class="uk-h6">
          Describes the redirect behavior of a request.
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="uk-form-row">
    <label class="uk-form-label" >LEAVING OUT LINK:</label>
    <div class="uk-form-controls">
      <div class="uk-grid">
        <div class="uk-width-4-10">
          <input type="text" name="setting_leaving_out" id="setting_leaving_out" value="@isset($setting_data['laravelsitemap_leaving_out']) {{$setting_data['laravelsitemap_leaving_out']}} @endisset " class="uk-form-width-medium">
        </div>
        <div class="uk-width-6-10">
          <span class="uk-h6">
          If you don't want a crawled link to appear in the sitemap. separate with commas (,) .<br>eg. admin, contact/list
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="uk-form-row">
    <button type="button" id="sitemapSetup" class="uk-button uk-button-primary">Save</button>
    <span id="sitemapsetupWait" class="uk-hidden"><i class="uk-icon-spinner uk-icon-spin"></i> Wait</span>
    <span id="sitemapsetupSuccess" class="uk-hidden"><i class="uk-icon uk-icon-check uk-text-success"> <span id="spanSuccessMsg">-</span></i></span>
    <span id="sitemapsetupError" class="uk-hidden"><i class="uk-icon uk-icon-info-circle uk-text-danger"> Error</i></span>
  </div>
</form>

</div>
@endsection

@push('package-scripts')
<script nonce="{{ csrf_token() }}">
$('#sitemapSetup').on('click', function() {
  statusAnimate('wait')
  $('#sitemapSetup').attr('disabled', true) // disabled button

  $.ajax({
    type: 'POST',
    url: '{{ route('admin.laravelsitemap.setting.savesetting') }}',
    data: {
      '_token': $("input[name='_token']").val(),
      'setting_data': $("#frmSitemapSetting").serialize()
    },
    success: function(data) {
      if (data.status==='success') {
        statusAnimate('success')
        $('#spanSuccessMsg').html(data.msg)
      } else {
        statusAnimate('error')
        alert(data.status);
      }
      $('#sitemapSetup').attr('disabled', false) // enable button
    },
    error:function (xhr, ajaxOptions, thrownError){
      statusAnimate('error')
      alert('Have some error from backend\n'+thrownError);
      $('#sitemapSetup').attr('disabled', false) // enable button
    }
  });

});

function statusAnimate(stat){
  $el_wait = $('#sitemapsetupWait')
  $el_success = $('#sitemapsetupSuccess')
  $el_error = $('#sitemapsetupError')

  $el_wait.addClass('uk-hidden')
  $el_success.addClass('uk-hidden')
  $el_error.addClass('uk-hidden')

  switch (stat) {
    case 'wait':
      $el_wait.removeClass('uk-hidden')
      break;
    case 'success':
      $el_success.removeClass('uk-hidden')
      break;
    case 'error':
      $el_error.removeClass('uk-hidden')
      break;
    default:
      $el_wait.addClass('uk-hidden')
      $el_success.addClass('uk-hidden')
      $el_error.addClass('uk-hidden')
      break;
  }
}
</script>
@endpush
