@extends('layouts.master-blue')
@section('title')
Lend Log
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('script-include')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
@stop
@section('style')
{{ HTML::style(asset('css/bootstrap-select.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/selectize.bootstrap3.css') }}" type="text/css">
{{ HTML::style(asset('css/bootstrap-tagsinput.css')) }}
{{ HTML::style(asset('css/style.min.css')) }}
<style>
	#page-body, #hide,#hide-notes,#lend-info{
		display:none;
	}
	.panel-padding{
		padding: 10px;
	}

	.line-either-side {
		overflow: hidden;
		text-align: center;
	}
	
	.line-either-side:before,
	.line-either-side:after {
		background-color: #e5e5e5;
		content: "";
		display: inline-block;
		height: 1px;
		position: relative;
		vertical-align: middle;
		width: 50%;
	}

	.line-either-side:before {
		right: 0.5em;
		margin-left: -50%;
	}

	.line-either-side:after {
		left: 0.5em;
		margin-right: -50%;
	}
	
</style>
@stop
@section('script-include')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/bootstrap-select.min.js')) }}
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-offset-3 col-md-6 panel panel-body" id="lend" style="padding: 10px;">
		<div style="padding:20px;">
		<h3 style="color:#337ab7;">Lend Form</h3>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('lend') }}">Lend Log</a>
          </li>
          <li class="active">Create</li>
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
			{{ Form::open(['class'=>'form-horizontal','method'=>'post','route'=>'lend.store','id'=>'lendForm']) }}
			<p class="text-muted line-either-side" id="borrower-details">Borrower</p>
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
			@if((isset($reservation->user) && $reservation->user->type != 'faculty') || (count($reservation) < 1 ) || $reservation == '' || empty($reservation))
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
			@endif
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
				<div class="col-xs-3">
					{{ Form::label('itemtype','Item') }}
				</div>
				<div class="col-xs-6"> 
		            {{ Form::text('temp-item',null,[
		              'id' => 'temp-item',
		              'class'=>'form-control',
		              'placeholder' => 'Property Number'
		            ]) }}
				</div>
				<div class="col-xs-3">
					<button type="button" class="btn btn-sm btn-success" id="add">ADD</button>
				</div>
			</div>
			<p class="text-muted line-either-side" id="item-details">Items</p>
			<table class="table table-hover table-condensed table-bordered" id="lendTable">
				<thead>
					<tr>
						<th class="col-sm-1">#</th>
						<th>Item</th>
						<th class="col-sm-1"></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<div class="form-group">
				<div class="col-sm-12">
				{{ Form::submit('Lend',[
					'class'=>'btn btn-lg btn-primary btn-block',
					'id'=>'request'
				]) }}
				</div>
			</div>
			@if(count($reservation) > 0 && isset($reservation->id))
			<input type="hidden" value="{{ $reservation->id }}" name="reservation" />
			@endif
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

		// $('#lendTable').DataTable();

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

	    $( "#lendForm" ).validate( {
	      rules: {
	        firstname: "required",
	        lastname: "required",
	      },
	      messages: {
	        firstname: "Please enter your firstname",
	        lastname: "Please enter your lastname",
	      },
	      errorElement: "em",
	      errorPlacement: function ( error, element ) {
	        // Add the `help-block` class to the error element
	        error.addClass( "help-block" );

	        // Add `has-feedback` class to the parent div.form-group
	        // in order to add icons to inputs
	        element.parents( ".form-group" ).addClass( "has-feedback" );

	        if ( element.prop( "type" ) === "checkbox" ) {
	          error.insertAfter( element.parent( "label" ) );
	        } else {
	          error.insertAfter( element );
	        }

	        // Add the span element, if doesn't exists, and apply the icon classes to it.
	        if ( !element.next( "span" )[ 0 ] ) {
	          $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
	        }
	      },
	      success: function ( label, element ) {
	        // Add the span element, if doesn't exists, and apply the icon classes to it.
	        if ( !$( element ).next( "span" )[ 0 ] ) {
	          $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
	        }
	      },
	      submitHandler: function(form) {
	        // do other things for a valid form
			name = $('#firstname').val() +  ' ' + $('#lastname').val()
			swal({
			  title: "Are you sure?",
			  text: "You will now be lending the items to " + name + '. Do you want to continue?',
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
					form.submit();
			  } else {
			    swal("Cancelled", "Request Cancelled", "error");
			  }
			});
	      },
	      highlight: function ( element, errorClass, validClass ) {
	        $( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
	        $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
	      },
	      unhighlight: function ( element, errorClass, validClass ) {
	        $( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
	        $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
	      }
	    } );

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

		$('#add').on('click',function(){
			row = parseInt($('#lendTable > tbody > tr:last').text())
			if(isNaN(row))
			{
				row = 1
			} else row++

			item = $('#temp-item').val()
			$('#temp-item').text("")
			$('#temp-item').val("")
			addForm(row,item)
		})

		function addForm(row,_item = "")
		{
			$('#lendTable > tbody').append(`
				<tr>
					<td>` + row	 + `</td>
					<td><input type="text" class="form-control text-center" readonly value="` + _item + `" name="item[` + _item + `]" style="border:none;background-color:white;" /></td>
					<td><button type="button" class="remove btn btn-sm btn-block btn-danger text-center"><span class="glyphicon glyphicon-remove"></span></button></td>
				</tr>
			`)
		}

		$('#lendTable').on('click','.remove',function(){
			$(this).parents('tr').remove()
		})

		function setDate(object){
				var object_val = $(object).val()
				var date = moment(object_val).format('MMM DD, YYYY');
				$(object).val(date);
		}

		@if(count($reservation) > 0)

		@if(isset($reservation->user))
		$('#lastname').val('{{ $reservation->user->lastname }}')
		@endif

		@if(isset($reservation->user))
		$('#firstname').val('{{ $reservation->user->firstname }}')
		@endif

		@if(isset($reservation->user))
		$('#middlename').val('{{ $reservation->user->middlename }}')
		@endif

		@if(isset($reservation->user))
		$('#lastname').val('{{ $reservation->user->lastname }}')
		@endif

		@endif
	});
</script>
@stop

