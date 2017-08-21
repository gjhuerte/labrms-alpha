@extends('layouts.master-blue')
@section('title')
Maintenance Activity | Edit
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/animate.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.min.css') }}" />
<style>
	#page-body{
		display:none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class='col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6'>
		<div class="panel panel-body ">
	        <legend><h3 class="text-muted">Maintenance Activity Update</h3></legend>
			<ol class="breadcrumb">
			  <li><a href="{{ url('maintenance/activity') }}">Maintenance Activity</a></li>
			  <li class="active">{{ $maintenanceactivity->activity }}</li>
			  <li class="active">Edit</li>
			</ol>
	        @if (count($errors) > 0)
	            <div class="alert alert-danger alert-dismissible" role="alert">
	            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <ul style='margin-left: 10px;'>
	                    @foreach ($errors->all() as $error)
	                        <li>{{ $error }}</li>
	                    @endforeach
	                </ul>
	            </div>
	        @endif
			{{ Form::open(['method'=>'put','route'=>array('activity.update',$maintenanceactivity->id),'class'=>'form-horizontal']) }}
				<!-- Title -->
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('type','Maintenance Type') }}
					</div>
					<div class="col-sm-6">
					  <input type="radio" id="corrective" name="maintenancetype" value='corrective' checked/> Corrective
					</div>
					<div class="col-sm-6">
					  <input type="radio" id="preventive" name="maintenancetype" value='preventive' /> Preventive
					</div>
				</div>
				<div id="preventive-info" class="col-sm-12 alert alert-success" role="alert" hidden>
					Machine maintenance or the preventive maintenance (PM) has the following meanings:
					<ul>
						<li>
						The care and servicing by personnel for the purpose of maintaining equipment in satisfactory operating condition by providing for systematic inspection, detection, and correction of incipient failures either before they occur or before they develop into major defects.
						</li>
						<li>
						Preventive maintenance tends to follow planned guidelines from time-to-time to prevent equipment and machinery breakdown
						</li>
						<li>
						The work carried out on equipment in order to avoid its breakdown or malfunction. It is a regular and routine action taken on equipment in order to prevent its breakdown.
						</li>
						<li>
						Maintenance, including tests, measurements, adjustments, parts replacement, and cleaning, performed specifically to prevent faults from occurring.
						</li>
					</ul>
				</div>
				<div class="col-sm-12 alert alert-warning" role="alert" id="corrective-info">
					Corrective maintenance is a maintenance task performed to identify, isolate, and rectify a fault so that the failed equipment, machine, or system can be restored to an operational condition within the tolerances or limits established for in-service operations
				</div>
				<!-- Title -->
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('activity','Activity Title') }}
					{{ Form::text('activity',$maintenanceactivity->activity,[
						'class'=>'form-control',
						'placeholder'=>'Title of the activity done'
					]) }}
					</div>
				</div>
				<!-- Details -->
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('details','Details') }}
					{{ Form::textarea('details',$maintenanceactivity->details,[
						'class'=>'form-control',
						'placeholder'=>'Description of the maintenance activity done'
					]) }}
					</div>
				</div>
				<div class="form-group">
					<div class=" col-md-12">
						<button type="submit" class="btn btn-lg btn-primary btn-block">Update</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop
@section('script')
<script>
	$(document).ready(function(){
		
		$('input[type=radio]').on('change',function(){
			console.log($('#preventive').is(':checked'))
			if($('#preventive').is(':checked'))
			{
				$('#corrective-info').hide()
				$('#preventive-info').show().animateCSS('fadeIn')
			} else {

				$('#preventive-info').hide()
				$('#corrective-info').show().animateCSS('fadeIn')
			}
		})

	    $.fn.extend({
	        animateCSS: function (animationName) {
	            var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
	            this.addClass('animated ' + animationName).one(animationEnd, function() {
	                $(this).removeClass('animated ' + animationName);
	            });
	        }
	    });

		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

	    @if($maintenanceactivity->type == 'Corrective')
	      $('#corrective').attr('checked','checked');
	    @else
	        $('#preventive').attr('checked','checked');
	    @endif

		$('#page-body').show();

	});
</script>
@stop
