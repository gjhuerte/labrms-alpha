@extends('layouts.master-blue')
@section('title')
Reservation
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/bootstrap-select.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/selectize.bootstrap3.css') }}" type="text/css">
{{ HTML::style(asset('css/datepicker.min.css')) }}
{{ HTML::style(asset('css/monthly.css')) }}
{{ HTML::style(asset('css/bootstrap-clockpicker.min.css')) }}
{{ HTML::style(asset('css/style.min.css')) }}
<style>
	#page-body, #hide,#hide-notes,#reservation-info{
		display:none;
	}
	.panel-padding{
		padding: 10px;
	}
	.datepicker{z-index:1151 !important;}
</style>
@stop
@section('script-include')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/datepicker.min.js')) }}
{{ HTML::script(asset('js/datepicker.en.js')) }}
{{ HTML::script(asset('js/monthly.js')) }}
{{ HTML::script(asset('js/bootstrap-clockpicker.min.js')) }}
{{ HTML::script(asset('js/bootstrap-select.min.js')) }}
@stop
@section('content')
<div class="container-fluid" id="page-body">
	@include('modal.reservation.calendar')
	@include('modal.reservation.rules')
	<div class="col-md-offset-3 col-md-6 panel panel-body" id="reservation" style="padding: 10px;">
		<div style="padding:20px;">
			<legend>
				<h3 style="color:#337ab7;">Reservation Form
					<div class="btn-group pull-right">
						<div class="btn-group">
						{{ Form::button('Show Rules',[
							'class'=>'btn btn-sm btn-primary',
							'id' => 'show-notes',
							'data-toggle'=>'modal',
							'data-target' => '#reservationRulesModal'
						]) }}
						</div>
						<div class="btn-group">
						{{ Form::button('Search Items Availability',[
							'class'=>'btn btn-sm btn-default',
							'id' => 'show',
							'data-toggle'=>'modal',
							'data-target' => 'reservationCalendarModal'
						]) }}
						</div>
					</div>
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
			<p class="text-primary"><strong>Note: </strong>3 day rule is not applied for your reservation</p>
			{{ Form::open(['class'=>'form-horizontal','method'=>'post','route'=>'reservation.store','id'=>'reservationForm']) }}
			@if(Auth::user()->type != 'faculty')
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
			@endif
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
			<!-- time_end -->
			<div class="form-group" id="time-end-group">
				<div class="col-sm-3">
				{{ Form::label('time_end','Time end') }}
				</div>
				<div class="col-sm-9">
				{{ Form::text('time_end',Input::old('time_end'),[
					'class'=>'form-control background-white',
					'placeholder'=>'Hour : Min',
					'id' => 'endtime',
					'readonly',
					'style'=>'background-color: #ffffff	'
				]) }}
				<span id="time-end-error-message" class="text-danger" style="font-size:10px;"></span>
				</div>
			</div>
			<!-- Item type -->
			<div class="form-group">
				<div class="col-xs-3">
					{{ Form::label('itemtype','Items') }}
				</div>
				<div class="col-xs-9"> 
		            {{ Form::select('items[]',['Empty list'=>'Empty list'],Input::old('items'),[
		              'id' => 'items',
		              'class'=>'form-control',
		              'multiple'
		            ]) }}
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
			<!-- Purpose -->
			<div class="form-group">
				<div class="col-sm-3">
				{{ Form::label('purpose','Purpose') }}
				</div>
				<div class="col-sm-9">
				{{ Form::select('purpose',['Loading all purpose...'],Input::old('purpose'),[
					'id' => 'purpose',
					'class'=>'form-control'
				]) }}
				<div class="checkbox">
					<label>
						<input type="checkbox" name="contains" id="contains"> Not in the list?
					</label>
				</div>
				{{ Form::textarea('description',Input::old('description'),[
					'id' => 'description',
					'class'=>'form-control',
					'placeholder'=>'Enter  details here...',
					'style' => 'display:none;'
				]) }}
				</div>
			</div>
		  <div class="form-group">
		    <div class="col-sm-12">
					<p class="text-muted text-justified">
						By clicking the request button, you agree to CCIS - LOO Terms and Conditions regarding reservation and lending equipments. <span class="text-danger"> The information filled up will no longer be editable and is final.</span>
					</p>
		    </div>
		  </div>
			<div class="form-group">
				<div class="col-sm-12">
				{{ Form::button('Request',[
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

		$('#show').click(function(){
			$('#reservationCalendarModal').modal('show');
		});

		$("#dateofuse").datepicker({
			language: 'en',
			showOtherYears: false,
			todayButton: true,
			minDate: new Date(),
			autoClose: true,
			onSelect: function(){
				$('#dateofuse').val(moment($('#dateofuse').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
			}
		});

		$("#dateofuse").val(moment('{{ $date }}').format('MMMM DD, YYYY'));

		$('#starttime').clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    // autoclose: true,
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

		$('#endtime').clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    // autoclose: true,
		    fromnow: 1800000,
		    default: 'now',
            donetext: 'Select',
            twelvehour: true,
            init: function(){
            	$('#endtime').val(moment().add("1800000").format("hh:mmA"))
            },
            afterDone: function() {
            	error('#time-end-error-message','*Time ended must be greater than time started')
            },
		});

		function error(attr2,message){
			if($('#endtime').val()){
				if(moment($('#starttime').val(),'hh:mmA').isBefore(moment($('#endtime').val(),'hh:mmA'))){
					$('#request').show(400);
					$('#time-end-error-message').html(``)
					$('#time-start-error-message').html(``)
					$('#time-end-group').removeClass('has-error');
					$('#time-start-group').removeClass('has-error');
				}else{
					$('#request').hide(400);
					$(attr2).html(message).show(400)
					$('#time-end-group').addClass('has-error');
					$('#time-start-group').addClass('has-error');
				}
			}
		}

		$('#request').click(function(){
			swal({
			  title: "Are you sure?",
			  text: "By submitting a request, you acknowledge our condition of three(3) working days in item reservation unless there is a special event or non-working holidays. Disregarding this notice decreases your chance of approval",
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
					$("#reservationForm").submit();
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
	        url: "{{ url('purpose') }}",
	        dataType: 'json',
	        success: function(response){
	          items = "";
	          for(ctr = 0;ctr<response.data.length;ctr++){
	            items += `<option value='` + response.data[ctr].title +`'>
	            ` + response.data[ctr].title + `
	            </option>`;
	          }

	          if(response.length == 0){
	              items += `<option>There are no purpose listed</option>`
	          }

	          $('#purpose').html("");
	          $('#purpose').append(items);
	        },
			complete: function(){
				
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

			$.ajax({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
				url: '{{ url("get/reservation/item/type/all") }}',
				type: 'get',
				dataType: 'json',
				success: function(response){
					options = '';

					for(ctr = 0;ctr< response.length;ctr++){
						options += "<option value='"+response[ctr].itemtype.name+"'>"+response[ctr].itemtype.name+"</option>"
					}

					if(response.length == 0)
					{
						options = "<option value='null'>There are no available items</option>";
					}

					$('#items').html("")
					$('#items').append(options)
					$('#items').selectpicker();
					@if(old('items',null) != null)
					$('#items').selectpicker('val',[
			            @foreach( Input::old("items") as $item )
			              {!! "'" . $item . "',"  !!}
			            @endforeach
			        ])
			        @endif
				},
				error: function(){
					$('#items').html("")
					$('#items').append("<option value='null'>There are no available items</option>")
				}
			})
		}

		$('#items').on('rendered.bs.select', function (e) {
			$('#page-body').show();
		});
	});
</script>
@stop

