@extends('orchestra/foundation::layouts.installer')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Setup The Application</h3>
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
