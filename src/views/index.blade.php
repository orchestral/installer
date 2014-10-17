@extends('orchestra/foundation::layout.main')

<?php

$databaseConnection = $checklist['databaseConnection'];
unset($checklist['databaseConnection']); ?>

@section('content')
<div class="row">
	<div class="three columns">
		<div class="list-group">
			<a href="{!! handles('orchestra::install') !!}" class="list-group-item active">
				{{ trans('orchestra/foundation::install.steps.requirement') }}
			</a>
			<a href="#" class="list-group-item disabled">
				{{ trans('orchestra/foundation::install.steps.account') }}
			</a>
			<a href="#" class="list-group-item disabled">
				{{ trans('orchestra/foundation::install.steps.done') }}
			</a>
		</div>
		<div class="progress">
			<div class="progress-bar progress-bar-success" style="width: 0%"></div>
		</div>
	</div>
	<div id="installation" class="six columns box form-horizontal">
		@include('orchestra/install::index._requirement')
		@include('orchestra/install::index._database')
		@include('orchestra/install::index._authentication')
	</div>
</div>
@stop
