@extends('layouts.master-blue')
@section('title')
Create Ticket
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('content')
<style>

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

	.material-switch > input[type="checkbox"] {
	    display: none;   
	}

	.material-switch > label {
	    cursor: pointer;
	    height: 0px;
	    position: relative; 
	    width: 40px;  
	}

	.material-switch > label::before {
	    background: rgb(0, 0, 0);
	    box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
	    border-radius: 8px;
	    content: '';
	    height: 16px;
	    margin-top: -8px;
	    position:absolute;
	    opacity: 0.3;
	    transition: all 0.4s ease-in-out;
	    width: 40px;
	}
	.material-switch > label::after {
	    background: rgb(255, 255, 255);
	    border-radius: 16px;
	    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
	    content: '';
	    height: 12px;
	    left: 2px;
	    margin-top: 0px;
	    position: absolute;
	    top: -6px;
	    transition: all 0.3s ease-in-out;
	    width: 12px;
	}
	.material-switch > input[type="checkbox"]:checked + label::before {
	    background: inherit;
	    opacity: 0.5;
	}
	.material-switch > input[type="checkbox"]:checked + label::after {
	    background: inherit;
	    left: 26px;
	}
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
			<h3 id="ticket-header" class="text-primary line-either-side"><span id="ticket-title-desc">Complaint</span> Ticket</h3>
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
				<div class="form-group">
					<div class="col-sm-3">
							<label>Ticket Number:</label> 
                   </div>
					<div class="col-sm-9">
							<div class="text-muted pull-right form-control" style="border:none;"><span class="text-muted">{{ $lastticket }}</span></div>
                   </div>
				</div>
				<div class="form-group">
					<div class="col-sm-3">
							<label>Date:</label>  
                   </div>
					<div class="col-sm-9">
							<div class="text-muted pull-right form-control" style="border:none;">
								<span class="text-muted">{{ Carbon\Carbon::now()->toDayDateTimeString() }}</span>
							</div>
                   </div>
				</div>
				<div class="form-group">
					<div class="col-sm-3">
	                        <label>Ticket Type:</label>
                   </div>
					<div class="col-sm-9">
						<div class="form-control " style="border:none;">
							<span id="tickettype-name" class="text-muted">Complaint</span>
	                        <div class="material-switch pull-right">
	                            <input id="tickettype" name="tickettype" type="checkbox"/>
	                            <label for="tickettype" class="label-danger"></label>
	                        </div>
	                    </div>
                   </div>
				</div>
				<!-- Item name -->
				<div class="form-group">
					<div class="col-sm-3">
					{{ Form::label('tag','Tag (Optional)') }}
					</div>
					<div class="col-sm-9">
					{{ Form::text('tag',Input::old('tag'),[
						'id' => 'tag',
						'class' => 'form-control',
						'placeholder' => 'Property Number, Room Name, Workstation Name'
					]) }}
					<p class="text-muted text-info" style="font-size:12px;">This field is for identifying the equipment, room, or workstation linked to this ticket.</p>
					<div id="tickettag"></div>
					</div>
				</div>
				<div class="form-group" id="author-form">
					<div class="col-sm-3">
						{{ Form::label('title','Title') }}
					</div>
					<div class="col-sm-9">
						{{ Form::text('title',Input::old('title'),[
						'class'=>'form-control',
						'placeholder' => 'Unique Ticket Identifier'
						]) }}
						<p class="text-muted" style="font-size:12px;">Note: Leaving this blank will label the title as 'Complaint'</p>
					</div>
				</div>

				@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2 )
				<div class="form-group" id="author-form">
					<div class="col-sm-3">
						{{ Form::label('author','Complainant') }}
					</div>
					<div class="col-sm-9">
						{{ Form::text('author',Input::old('author'),[
						'class'=>'form-control',
						'placeholder' => Auth::user()->firstname.' '.Auth::user()->lastname
						]) }}
						<p class="text-muted text-warning" style="font-size:12px;">Leave this field blank if you're the complainant.</p>
					</div>
				</div>
				@endif

				@if(Auth::user()->accesslevel == 0)
				<div class="form-group" id="author-form">
					<div class="col-sm-3">
						{{ Form::label('staffassigned','Staff Assigned') }}
					</div>
					<div class="col-sm-9">
						{{ Form::select('staffassigned',$staff,Input::old('staffassigned'),[
						'class'=>'form-control',
						'id' => 'staffassigned'
						]) }}
					</div>
				</div>
				@endif
				
				<div class="form-group">
					<!-- description -->
					<div class="col-sm-12">
						{{ Form::label('description','Details') }}
						<p class="text-muted" style="font-size:12px;">This field is required to further explain the details of the ticket</p>	
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

		$('#tickettype').on('click',function(){
			if($('#tickettype').is(':checked'))
			{
				$('#tickettype-name').text('Incident')
				$('#ticket-title-desc').text('Incident')
				$('#ticket-header').addClass('text-danger')
				$('#ticket-header').removeClass('text-primary')
			}
			else
			{
				$('#tickettype-name').text('Complaint')	
				$('#ticket-title-desc').text('Complaint')	
				$('#ticket-header').removeClass('text-danger')
				$('#ticket-header').addClass('text-primary')
			}
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