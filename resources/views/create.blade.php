@extends('orchestra/foundation::layouts.setup')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ @\trans('orchestra/installer::title.setup') }}</h3>
  </div>
  <div class="panel-body">
    <progress progress="75"></progress>
    {{ $form }}
  </div>
</div>
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').$mount('body')
</script>
@endpush
