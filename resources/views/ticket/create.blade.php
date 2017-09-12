@extends('layouts.master-blue')
@section('title')
Ticket | Create
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('content')
<style>

	#page-body{
		display:none;
	}

	.panel-padding{
		padding: 25px;
		margin: 10px;
	}
</style>
<div class="container-fluid" id="page-body" style="margin-top: 40px;">
	<div class='col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6'>  
		<div class="panel panel-body panel-padding">
			<legend><h3 style="color:#337ab7;">Create Ticket</h3></legend>
			<ul class="breadcrumb">
				<li>
					<a href="{{ url('ticket') }}">Ticket</a>
				</li>
				<li>
					Create
				</li>
			</ul>
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
			{{ Form::open(['method'=>'post','route'=>'ticket.store','class'=>'form-horizontal','id'=>'ticketForm']) }}
				<h4 class="text-muted pull-left">Ticket Number:  {{ $lastticket }}</h4>
				<h4 class="text-muted pull-right">{{ Carbon\Carbon::now()->toDayDateTimeString() }}</h4>
				<!-- Item name -->
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('tag','Tag (Optional)') }}
					<p class="text-muted text-info">This field is for identifying the equipment, room, or workstation linked to this ticket. Note: Use the property number for equipments</p>
					{{ Form::text('tag',Input::old('tag'),[
						'id' => 'tag',
						'class' => 'form-control',
						'placeholder' => 'Equipment Property Number, Room Name'
					]) }}
					</div>
				</div>
				<div id="tickettag"></div>
				<div class="form-group" id="author-form">
					<div class="col-sm-12">
						{{ Form::label('title','Title') }}
						{{ Form::text('title',Input::old('title'),[
						'class'=>'form-control',
						'placeholder' => 'Unique Ticket Identifier'
						]) }}
						<p class="text-muted">Note: Leaving this blank will label the title as 'Complaint'</p>
					</div>
				</div>

				@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2 )
				<div class="form-group" id="author-form">
					<div class="col-sm-12">
						{{ Form::label('author','Complainant') }}
						{{ Form::text('author',Input::old('author'),[
						'class'=>'form-control',
						'placeholder' => Auth::user()->firstname.' '.Auth::user()->lastname
						]) }}
						<p class="text-muted text-warning">Leave this field blank if you're the complainant.</p>
					</div>
				</div>
				@endif
				
				<div class="form-group">
					<!-- description -->
					<div class="col-sm-12">
						{{ Form::label('description','Details') }}
						<p class="text-muted">This field is required to further explain the details of the ticket</p>
						{{ Form::textarea('description',Input::old('description'),[
							'class'=>'form-control',
							'placeholder'=>'Enter ticket details here...'
						]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::submit('Create',[
						'class'=>'btn btn-lg btn-block btn-md btn-primary'
					]) }}
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

		$('#tag').change(function(){
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: 'get',
                url: "{{ url('get/ticket/tag') }}" + '?id=' + $('#tag').val(),
                dataType: 'json',
                success: function(response){
					if(response == 'error')
					{
						$('#tickettag').html(`
							<div class="alert alert-warning alert-dismissible" role="alert">
	  							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  							<strong>Warning!</strong> Tag inputted doesnt match any record.This wont be linked to any equipment,workstation,or room
							</div>`)
					}
					else
					{
									
						if(response.propertynumber)
						{

							$('#tickettag').html(`
								<div class="panel panel-info">
									<div class="panel-heading">
										Item Profile
									</div>
									<ul class="list-group">
									  <li class="list-group-item">Property Number:  `+response.propertynumber+`<span id="transfer-date"></span></li>
									  <li class="list-group-item">Serial Number: `+response.serialnumber+` <span id="transfer-tag"></span></li>
									  <li class="list-group-item">Status: `+response.status+`<span id="transfer-title"></span></li>
									</ul>
								</div>
							`)
						}
						else if(response.systemunit_id)
						{

							$('#tickettag').html(`
								<div class="panel panel-info">
									<div class="panel-heading">
										Workstation Information
									</div>
									<ul class="list-group">
									  <li class="list-group-item">Workstation Name:  `+response.name+`</li>
									  <li class="list-group-item">System Unit:  `+response.systemunit.propertynumber+`</li>
									  <li class="list-group-item">Monitor:  `+response.monitor.propertynumber+`</li>
									  <li class="list-group-item">AVR: `+response.avr.propertynumber+`</li>
									  <li class="list-group-item">Keyboard:  `+response.keyboard.propertynumber+`</li>
									  <li class="list-group-item">Mouse:  `+response.mouse+`</li>
									  <li class="list-group-item">Status: `+response.systemunit.status+`</li>
									</ul>
								</div>
							`)
						}
						else if(response.name)
						{

							$('#tickettag').html(`
								<div class="panel panel-info">
									<div class="panel-heading">
										Room Information
									</div>
									<ul class="list-group">
									  <li class="list-group-item">Room Name:  `+response.name+`</li>
									  <li class="list-group-item">Category:  `+response.description+`</li>
									</ul>
								</div>
							`)
						}
					}
				}
			})
		})

		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif


		$('#page-body').show();
	})
</script>
@stop