@extends('orchestra/foundation::layouts.setup')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ @\trans('orchestra/installer::title.thank-you') }}</h3>
  </div>
  <div class="panel-body">
    <progress progress="100"></progress>
  </div>
  <div class="panel-footer">
    <a href="{{ \handles(Orchestra\Installation\Installation::$redirectAfterInstalled) }}" class="btn btn-success btn-lg btn-block">
      {{ @\trans('orchestra/foundation::title.login') }}
    </a>
  </div>
</div>
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').$mount('body')
</script>
@endpush
