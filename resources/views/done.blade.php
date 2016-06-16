@extends('orchestra/foundation::layouts.installer')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Thank you for choosing Orchestra Platform</h3>
  </div>
  <div class="panel-body">
    <progress progress="100"></progress>
  </div>
  <div class="panel-footer">
    <a href="{{ handles('orchestra::login') }}" class="btn btn-success btn-lg btn-block">
      Proceed to Application
    </a>
  </div>
</div>
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').$mount('body')
</script>
@endpush
