@extends('layouts.master-blue')
@section('title')
Lend Log
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/bootstrap-select.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/selectize.bootstrap3.css') }}" type="text/css">
{{ HTML::style(asset('css/bootstrap-tagsinput.css')) }}
{{ HTML::style(asset('css/datepicker.min.css')) }}
{{ HTML::style(asset('css/monthly.css')) }}
{{ HTML::style(asset('css/bootstrap-clockpicker.min.css')) }}
{{ HTML::style(asset('css/style.min.css')) }}
<style>
	#page-body, #hide,#hide-notes,#lend-info{
		display:none;
	}
	.panel-padding{
		padding: 10px;
	}
	
</style>
@stop
@section('script-include')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/datepicker.min.js')) }}
{{ HTML::script(asset('js/datepicker.en.js')) }}
{{ HTML::script(asset('js/bootstrap-clockpicker.min.js')) }}
{{ HTML::script(asset('js/bootstrap-select.min.js')) }}
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-offset-3 col-md-6 panel panel-body" id="lend" style="padding: 10px;">
		<div style="padding:20px;">
			<legend>
				<h3 style="color:#337ab7;">Lend Creation Form
				</h3>
			</legend>
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
			{{ Form::open(['class'=>'form-horizontal','method'=>'post','route'=>'lend.store','id'=>'lendForm']) }}
			<!-- Item type -->
			<div class="form-group">
				<div class="col-xs-3">
					{{ Form::label('itemtype','Item') }}
				</div>
				<div class="col-xs-9"> 
		            {{ Form::text('item',Input::old('item'),[
		              'id' => 'item',
		              'class'=>'form-control',
		              'placeholder' => 'Property Number'
		            ]) }}
				</div>
			</div>
			<!-- First name -->
			<div class="form-group">
				<div class="col-xs-3">
					{{ Form::label('firstname','Firstname') }}
				</div>
				<div class="col-xs-9"> 
		            {{ Form::text('firstname',Input::old('firstname'),[
		              'id' => 'firstname',
		              'class'=>'form-control',
		              'placeholder' => 'Firstname'
		            ]) }}
				</div>
			</div>
			<!-- Middle name -->
			<div class="form-group">
				<div class="col-xs-3">
					{{ Form::label('middlename','Middlename') }}
				</div>
				<div class="col-xs-9"> 
		            {{ Form::text('middlename',Input::old('middlename'),[
		              'id' => 'middlename',
		              'class'=>'form-control',
		              'placeholder' => 'Middlename'
		            ]) }}
				</div>
			</div>
			<!-- Last name -->
			<div class="form-group">
				<div class="col-xs-3">
					{{ Form::label('lastname','Lastname') }}
				</div>
				<div class="col-xs-9"> 
		            {{ Form::text('lastname',Input::old('lastname'),[
		              'id' => 'lastname',
		              'class'=>'form-control',
		              'placeholder' => 'Lastname'
		            ]) }}
				</div>
			</div>
			<!-- Course Year and Section -->
			<div class="form-group">
				<div class="col-xs-3">
					{{ Form::label('courseyearsection','Course Year and Section') }}
				</div>
				<div class="col-xs-9"> 
		            {{ Form::text('courseyearsection',Input::old('courseyearsection'),[
		              'id' => 'courseyearsection',
		              'class'=>'form-control',
		              'placeholder' => 'Course Year-Section'
		            ]) }}
				</div>
			</div>
			<!-- creator name -->
			<div class="form-group">
				<div class="col-sm-3">
				{{ Form::label('name','Faculty-in-charge') }}
				</div>
				<div class="col-sm-9">
				{{
					Form::select('name',[],Input::old('name'),[
					'id'=>'name',
					'class'=>'form-control'
				]) }}
				</div>
			</div>
			<!-- date of use -->
			<div class="form-group">
				<div class="col-sm-3">
				{{ Form::label('dateofuse','Date of Use',[
    				'data-language'=>"en"
    			]) }}
				</div>
				<div class="col-sm-9">
				{{ Form::text('dateofuse',Input::old('dateofuse'),[
					'id' => 'dateofuse',
					'class'=>'form-control',
					'placeholder'=>'MM | DD | YYYY',
					'readonly',
					'style'=>'background-color: #ffffff	'
				]) }}
				</div>
			</div>
			<!-- time started -->
			<div class="form-group" id="time-start-group">
				<div class="col-sm-3">
				{{ Form::label('time_start','Time started') }}
				</div>
				<div class="col-sm-9">
				{{ Form::text('time_start',Input::old('time_start'),[
					'class'=>'form-control',
					'placeholder'=>'Hour : Min',
					'id' => 'starttime',
					'readonly',
					'style'=>'background-color: #ffffff	'
				]) }}
				<span id="time-start-error-message" class="text-danger" style="font-size:10px;"></span>
				</div>
			</div>
			<!-- Location -->
			<div class="form-group">
				<div class="col-sm-3">
				{{ Form::label('location','Location') }}
				</div>
				<div class="col-sm-9">
				{{
					Form::select('location',[],Input::old('location'),[
					'id'=>'location',
					'class'=>'form-control'
				]) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
				{{ Form::button('Submit',[
					'class'=>'btn btn-lg btn-primary btn-block',
					'id'=>'request'
				]) }}
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/bootstrap-tagsinput.min.js')) }}
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript" src="{{ asset('js/standalone/selectize.js') }}"></script>
<script>
	$(document).ready(function(){

		$('#contains').change(function(){
			$('#purpose').toggle(400)
			$('#description').toggle(400)
		})

		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

		$("#dateofuse").datepicker({
			language: 'en',
			showOtherYears: false,
			todayButton: true,
			autoClose: true,
			onSelect: function(){
				$('#dateofuse').val(moment($('#dateofuse').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
			}
		});

		$("#dateofuse").val('{{ Carbon\Carbon::now()->toFormattedDateString() }}');

		$('#starttime').clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    autoclose: true,
		    default: 'now',
            donetext: 'Select',
            twelvehour: true,
            init: function(){
            	$('#starttime').val(moment().format("hh:mmA"))
            },
            afterDone: function() {
            	error('#time-start-error-message','*Time started must be less than time end')
            },
		});

		$('#request').click(function(){
			swal({
			  title: "Are you sure?",
			  text: "By lending this equipment, you agreed to the policies and terms of the Laboratory Operations Office.",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonColor: "#DD6B55",
			  confirmButtonText: "Yes, submit it!",
			  cancelButtonText: "No, cancel it!",
			  closeOnConfirm: false,
			  closeOnCancel: false
			},
			function(isConfirm){
			  if (isConfirm) {
					$("#lendForm").submit();
			  } else {
			    swal("Cancelled", "Request Cancelled", "error");
			  }
			});
		});

		init();

		function init(){
	      $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
	        type: 'get',
	        url: "{{ url('room') }}",
	        dataType: 'json',
	        success: function(response){
	          items = "";
	          for(ctr = 0;ctr<response.data.length;ctr++){
	            items += `<option value=`+response.data[ctr].name+`>
	            `+response.data[ctr].name+`
	            </option>`;
	          }

	          if(response.length == 0){
	              items += `<option>There are no available room</option>`
	          }

	          $('#location').html("");
	          $('#location').append(items);
	        },
					complete: function(){

						$('#location').selectize({
								create: true,
								sortField: {
										field: 'text',
										direction: 'asc'
								},
								dropdownParent: 'body'
						})

						$('#location').val({{ Input::old('location') }})
					}
	      });

	      $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
	        type: 'get',
	        url: "{{ url('faculty') }}",
	        dataType: 'json',
	        success: function(response){
	          items = "";
	          for(ctr = 0;ctr<response.data.length;ctr++){
							lastname = response.data[ctr].lastname;
							firstname = response.data[ctr].firstname;
							if(response.data[ctr].middlename){
								middlename = response.data[ctr].middlename;
							}else{
								middlename = "";
							}
				name = lastname + ', ' + firstname + ' ' + middlename
	            items += `<option value='`+ name +`'>
	            ` + name + `
	            </option>`;
	          }

	          if(response.length == 0){
	              items += `<option>There are no available faculty</option>`
	          }

	          $('#name').html("");
	          $('#name').append(items);
	        },
					complete: function(){

						$('#name').selectize({
								create: true,
								sortField: {
										field: 'text',
										direction: 'asc'
								},
								dropdownParent: 'body'
						})

						$('#name').val({{ Input::old('name') }})
					}
	      });
		}

		$('#page-body').show();
	});
</script>
@stop

