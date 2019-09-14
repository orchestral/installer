@extends('orchestra/foundation::layouts.setup')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ @\trans('orchestra/installer::title.requirement') }}</h3>
  </div>
  <div class="panel-body">
    <div class="progress">
      @if($requirements->isInstallable())
      <progress progress="25"></progress>
      @else
      <progress type="danger" progress="1"></progress>
      @endif
    </div>
    <ul class="faq__questions">
      @foreach($requirements as $uid => $requirement)
      <li>
        <a href="#" data-toggle="collapse" data-target="#faq__question_{{ $requirement->sanitizedUid() }}" class="" aria-expanded="true">
          {!! $requirement->title() !!}
          @if($requirement->check())
          <span class="label label-success pull-right">{{ @trans('orchestra/installer::label.yes') }}</span>
          @else
          <span class="label label-danger pull-right">{{ @trans('orchestra/installer::label.no') }}</span>
          @endif
        </a>
        <div class="collapse" id="faq__question_{{ $requirement->sanitizedUid() }}" aria-expanded="true">
          <p>{!! $requirement->description() !!}</p>
          @if($requirement->hasError())
          <div class="alert alert-warning" role="alert">
            {!! $requirement->error() !!}
          </div>
          @endif
        </div>
      </li>
      @endforeach
    </ul>
  </div>
  <div class="panel-footer">
    @if($requirements->isInstallable())
    <a href="{{ \handles('orchestra::install/prepare') }}" class="btn btn-primary btn-lg btn-block">
      {{ \trans('orchestra/foundation::label.next') }}
    </a>
    @endif
  </div>
</div>
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').$mount('body')
</script>
@endpush
