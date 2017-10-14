@extends('layouts.master-blue')
@section('title')
Maintenance Ticket
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('content')
<style>

	#page-body,#undermaintenance-tag{
		display:none;
	}

	.panel-padding{
		padding: 25px;
		margin: 10px;
	}

	.panel{
		box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	}
</style>
<div class="container-fluid" id="page-body" style="margin-top: 40px;">
	<div class='col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6'>  
		<div class="panel panel-body panel-padding">
			<legend><h3 style="color:#337ab7;">Maintenance Ticket</h3></legend>
			<ul class="breadcrumb">
				<li>
					<a href="{{ url('ticket') }}">Ticket</a>
				</li>
				<li>
					Maintenance
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
			{{ Form::open(['method'=>'post','route'=>'ticket.maintenance','class'=>'form-horizontal','id'=>'ticketForm']) }}
				<div class="clearfix"></div>
				<div class="form-group">
					<div class="col-sm-3">
						{{ Form::label('Ticket Number:') }}
					</div>
					<div class="col-sm-9">
						{{ Form::text('ticketnumber',$lastticket ,[
							'class' => 'form-control',
							'readonly',
							'style' => 'border:none;background-color: white;'
						]) }}
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="form-group">
					<div class="col-sm-3">
						{{ Form::label('Date') }}
					</div>
					<div class="col-sm-9">
						{{ Form::text('date',Carbon\Carbon::now()->toDayDateTimeString(),[
							'class' => 'form-control',
							'readonly',
							'style' => 'border:none;background-color: white;'
						]) }}
					</div>
				</div>
				<!-- Item name -->
				<div class="clearfix"></div>
				<div class="form-group">
					<div class="col-sm-3">
					{{ Form::label('tag','Tag') }}
					</div>
					<div class="col-sm-9">
						{{ Form::text('tag',Input::old('tag'),[
							'id' => 'tag',
							'class' => 'form-control',
							'placeholder' => 'Equipment Property Number, Room Name, Workstation Name'
						]) }}
						<p class="text-muted" style="font-size:12px;">This field is for identifying the equipment, room, or workstation linked to this ticket.</p>
						<div id="tickettag"></div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3">
					{{ Form::label('activity','Activity Done') }}
					</div>
					<div class="col-sm-9">
						<!-- Category -->
						<div id="activity-field">
						{{ Form::select('activity',$activity,Input::old('activity'),[
							'id' => 'activity',
							'class' => 'form-control'
						]) }}
							<div id="activity-description"></div>
						</div>
						<div id="details-field" hidden>
							{{ Form::textarea('description',Input::old('description'),[
								'class'=>'form-control',
								'placeholder'=>'Enter ticket details here...'
							]) }}
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="contains" id="contains"> Not in the list?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
					</div>
				</div>
				{{-- 
				<div class="form-group" id="undermaintenance-tag">
					<div class="col-sm-12">
						<input type="checkbox" name="underrepair" />
						<label for="">Set as 'Undermaintenance'</label> 
						<p class="text-muted">Clicking this checkbox will set the item/equipment/pc as 'undermaintenance' </p>
					</div>
				</div> --}}
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

		$('#activity').change(function(){
			setDetailsField()
		});

		$('#contains').change(function(){
			$('#activity-field').toggle(400)
			$('#details-field').toggle(400)
		})

		$('#tag').change(function(){
			_url = "{{ url('get/ticket/tag') }}" + '?id=' + $('#tag').val()
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: 'get',
				url: _url,
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
						if(response.systemunit_id != null || response.monitor_id != null)
						{
							$('#tickettag').html(`
									<ul class="list-group">
									  <li class="list-group-item">Workstation Name:  `+response.name+`</li>
									  <li class="list-group-item">System Unit:  `+response.systemunit.propertynumber+`</li>
									  <li class="list-group-item">Monitor:  `+response.monitor.propertynumber+`</li>
									  <li class="list-group-item">AVR: `+response.avr.propertynumber+`</li>
									  <li class="list-group-item">Keyboard:  `+response.keyboard.propertynumber+`</li>
									  <li class="list-group-item">Mouse:  `+response.mouse+`</li>
									  <li class="list-group-item">Status: `+response.systemunit.status+`</li>
									</ul>
							`);
							$('#undermaintenance-tag').show()
						}

						if(response.propertynumber != null)
						{
							$('#tickettag').html(`
									<ul class="list-group">
									  <li class="list-group-item">Property Number:  `+response.propertynumber+`<span id="transfer-date"></span></li>
									  <li class="list-group-item">Serial Number: `+response.serialnumber+` <span id="transfer-tag"></span></li>
									  <li class="list-group-item">Status: `+response.status+`<span id="transfer-title"></span></li>
									</ul>
							`);
							$('#undermaintenance-tag').show()
						}

						if(response.name != null)
						{
							$('#tickettag').html(`
								<ul class="list-group">
								  <li class="list-group-item">Room Name:  `+response.name+`</li>
								  <li class="list-group-item">Category:  `+response.description+`</li>
								</ul>

								<div id="item-list"></div>
							`);

							addItemsUnderTheRoom()

							$('#undermaintenance-tag').hide()
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

		function setDetailsField()
		{
			_url = "{{ url('maintenance/activity') }}"+ '?id=' + $('#tag').val()
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: 'get',
				url: _url,
				dataType: 'json',
				success: function(response)
				{
					if(response)
					{
						$('#activity-description').html(`
							<div class="alert alert-warning">
								<strong>Details: </strong> ` + response.details + `
							</div>
						`)
					}
				}
			})
		}

		function addItemsUnderTheRoom()
		{
			_url = "{{ url('inventory/room') }}"+ '/' + $('#tag').val()
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: 'get',
				url: _url,
				dataType: 'json',
				success: function(response)
				{
					html = '';
					$.each(response,function(index,callback)
					{
						html += `
							<div class="checkbox">
								<label>
									<input type="checkbox" value="`+callback.item+`" name="item[]"> ` + callback.type + ` - ` + callback.item + `
								</label>
							</div>
						`
					})

					$('#item-list').html(html)
				}
			})
		}

		function setDetailsField()
		{
			_url = "{{ url('maintenance/activity') }}"+ '/' + $('#activity').val()
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: 'get',
				url: _url,
				dataType: 'json',
				success: function(response)
				{
					if(response)
					{
						$('#activity-description').html(`
							<div class="alert alert-warning">
								<strong>Details: </strong> ` + response.details + `
							</div>
						`)
					}
				}
			})
		}

		$('#include-items').on('change',function(){
			alert('changed')
			$('#roominventory-list').toggle()
		})

		setDetailsField()
		$('#page-body').show();
	})
</script>
@stop