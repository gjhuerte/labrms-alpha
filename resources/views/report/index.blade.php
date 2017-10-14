@extends('layouts.master-blue')
@section('title')
Report::Generate
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
{{ HTML::style(asset('css/font-awesome.min.css')) }}
{{ HTML::style(asset('css/datepicker.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body{
		display: none;
	}

	.form-control{
		border-radius:0px;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
		<div class="row">
		{{ Form::open(['method' => 'post' , 'url' => array('reports') ]) }}
			<div class="col-md-6" style="margin-left:0px;padding-left:0px;">
				<div class="col-md-4">
					<div class="form-group">
					{{ Form::select('type',$type,Input::old('type'),[
						'id' => 'type',
						'class' => 'form-control'
					]) }}
					</div>
				</div>
				<div class="col-md-3" id="category-field" hidden>
					<div class="form-group">
					{{ Form::select('category',$category,Input::old('category'),[
						'id' => 'category',
						'class' => 'form-control'
					]) }}
					</div>
				</div>
				<div class="col-md-3" id="room-field" hidden>
					<div class="form-group">
					{{ Form::select('room',$room,Input::old('room'),[
						'id' => 'room',
						'class' => 'form-control'
					]) }}
					</div>
				</div>
				<div class="col-md-3" id="date-field" hidden>
					<div class="form-group">
					{{ Form::text('Date',Input::old('date'),[
						'id' => 'date',
						'class' => 'form-control',
						'readonly',
						'style'=> 'background-color:white;'
					]) }}
					</div>
				</div>
			</div>
			<div class="col-md-6" style="margin-right:0px;">
				<div class="btn-group btn-group pull-right">
					<div class="btn-group">
						<button type="button" id="generate" class="btn btn-md btn-success" style="border:none;padding:8px;margin-left:5px;border-radius:0px;">
							<i class="fa fa-cogs" aria-hidden="true"></i>
							Generate
						</button>
					</div>
					<div class="btn-group">
						<button type="button" id="print" class="btn btn-md btn-primary" style="border:none;padding:8px;margin-left:5px;border-radius:0px;">
							<i class="fa fa-print" aria-hidden="true"></i>
							Print
						</button>
					</div>
				</div>
			</div>
		{{ Form::close() }}
		</div>
		<!-- 16:9 aspect ratio -->
		<div class="embed-responsive embed-responsive-16by9" style="background-color:white;">
		  <iframe id="report-frame" name="reportframe" class="embed-responsive-item"></iframe>
		</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/datepicker.min.js')) }}
{{ HTML::script(asset('js/datepicker.en.js')) }}
<script type="text/javascript">
	$(document).ready(function() {

		$('#generate').on('click',function(){
			changeURL()
		})

		$('#type').on('change',function(){
			changeType()
		})

		// changeURL();
		changeDate();

		$('#category').on('change',function(){
			changeDate()
		})

		$('#print').on('click',function(){

            window.frames['reportframe'].focus();
            window.frames['reportframe'].print();
		})

		function changeType()
		{
			type = $('#type').val()

			if(type == 'equipmentmasterlist')
			{
				$('#date-field').hide()
				$('#category-field').hide()
				$('#room-field').hide()
			}

			if(type == 'deployment')
			{
				$('#date-field').show()
				$('#category-field').show()
				$('#room-field').hide()
			}

			if(type == 'preventivemaintenance')
			{
				$('#date-field').show()
				$('#category-field').show()
				$('#room-field').hide()

			}

			if(type == 'reservation')
			{
				$('#date-field').show()
				$('#category-field').show()
				$('#room-field').hide()

			}

			if(type == 'transfer')
			{
				$('#date-field').show()
				$('#category-field').show()
				$('#room-field').hide()
			}

			if(type == 'incident')
			{
				$('#date-field').show()
				$('#category-field').show()
				$('#room-field').hide()
			}

			if(type == 'profiling')
			{
				$('#date-field').show()
				$('#category-field').show()
				$('#room-field').hide()
			}

			if(type == 'complaints')
			{
				$('#date-field').show()
				$('#category-field').show()
				$('#room-field').hide()

			}

			if(type == 'workstationinventory')
			{
				$('#date-field').hide()
				$('#category-field').hide()
				$('#room-field').show()

			}

			if(type == 'roominventory')
			{
				$('#date-field').hide()
				$('#category-field').hide()
				$('#room-field').show()

			}

		}

		function changeURL()
		{
			type = $('#type').val()
			category = $('#category').val()
			date = $('#date').val()
			room = $('#room').val()
			url = "{{ url('reports') }}" + '/' + type + '?' + 'category=' + category + '&&date=' + date + '&&room=' + room
			try
			{
				$('#report-frame').attr('src',url)
			}catch(e){
				
			}
		}

		function changeDate()
		{

			category = $('#category').val()

			if(category == 'daily')
			{

				datepicker = $("#date").datepicker({
					language: 'en',
					showOtherYears: false,
					todayButton: true,
					autoClose: true,
					onSelect: function(){
						$('#date').val(moment($('#date').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
					}
				});

				$("#date").val(moment().format('MMM DD, YYYY'));

			}
			else
			{

				// var myDatepicker = $('#date').datepicker().data('datepicker');
				// myDatepicker.destroy();
			}

			if(category == 'weekly')
			{
				swal('Note!',"The week starts on Monday. The week will be extracted from the date picked ",'warning')
			}

			if(category == 'monthly')
			{
				swal('Note!',"Month will be extracted from the date picked",'warning')
			}

			if(category == 'annually')
			{
				swal('Note!',"Year chosen will be extracted from the date picked",'warning')
			}
		}

		$("#date").val(moment().format('MMM DD, YYYY'));

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

		$('#page-body').show();
	} );
</script>
@stop
