<div class="modal fade" id="resolveTicketModal" tabindex="-1" role="dialog" aria-labelledby="resolveTicketModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
			
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<legend>
				<h3 style="color:#337ab7;">Action Taken</h3>
			</legend>
			{{ Form::open(['method'=>'post','route'=>'ticket.resolve','class'=>'form-horizontal','id'=>'ticketForm']) }}
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
				<input type="hidden" value="" name="id" id="resolve-id" />
				<div class="clearfix"></div>
				<div class="form-group">
					<div class="col-sm-3">
						{{ Form::label('Staff') }}
					</div>
					<div class="col-sm-9">
						{{ Form::text('author',Auth::user()->firstname . " " . Auth::user()->lastname,[
							'class' => 'form-control',
							'readonly',
							'style' => 'border:none;background-color: white;'
						]) }}
					</div>
				</div>
				<div id="resolve-item-form" hidden>
					{{ Form::label('Item Information') }}
					<div id="resolve-item-information"></div>
				</div>
				<div class="form-group">
					<div class="col-sm-3">
					{{ Form::label('Activity Done') }}
					</div>
					<!-- Category -->
					<div class="col-sm-9">
						<div id="activity-field">
							{{ Form::select('activity',[ null => null ],Input::old('activity'),[
								'id' => 'resolve-activity',
								'class' => 'form-control'
							]) }}
							<div id="activity-description"></div>
						</div>
						<div id="details-field" hidden>
							{{ Form::textarea('details',Input::old('details'),[
								'class' => 'form-control',
								'placeholder' => 'Include here the activity done to resolve the issue'
							]) }}
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="contains" id="contains"> Not in the list?
							</label>
						</div>
					</div>
				</div>
				<input type="hidden" id="item-tag" value="" />
				<div id="resolve-equipment">
					<div class="form-group" id="undermaintenance-form" hidden>
						<div class="col-sm-12">
							<input type="checkbox" name="underrepair" />
							<label for="">Set as 'Undermaintenance'</label> 
							<p class="text-muted">Clicking this checkbox will set the item/equipment/pc as 'undermaintenance' </p>
						</div>
					</div>
					<div class="form-group" id="working-form" hidden>
						<div class="col-sm-12">
							<input type="checkbox" name="working" />
							<label for="">Set as 'working'</label> 
							<p class="text-muted">Clicking this checkbox will set the item/equipment/pc as 'working' </p>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<input type="checkbox" name="close" />
						<label for="">Close Ticket?</label> 
						<p class="text-muted" style="font-size:12px;">This will close the ticket. The Administrator can reopen the ticket if he/she is not satisfied with the result</p>
					</div>
				</div>	
				<div class="form-group">
					<div class="col-sm-12">
						{{ Form::submit('Okay',[
						'class'=>'btn btn-block btn-flat pull-right btn-md btn-primary',
						'style'=>'padding: 10px'
						]) }}
					</div>
				</div>
			{{ Form::close() }}
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>
<script>
$('#resolveTicketModal').on('show.bs.modal',function(){
	item_id = $('#item-tag').val()

	$('#resolve-activity').on('change',function(){
		setDetailsField()
	});

	$('#contains').on('change',function(){
		$('#activity-field').toggle(400)
		$('#details-field').toggle(400)
	})

	$.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{ url('get/maintenance/activity') }}",
        type: 'get',
        data: {
      		type: 'corrective'  	
        },
        dataType: 'json',
        success: function(response){
        	options = '';
        	$.each( response, function(index,callback) {
        		options += ` <option value='` + index + `'>` + callback + `</option> `
        	} )

        	if(response.length == 0)
        	{
        		options = ` <option>No suggestions</option> `
        	}

        	$('#resolve-activity').html(``)
        	$('#resolve-activity').append(options)
			setDetailsField()
        }
	})

	if(item_id)
	{
		$.ajax({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
			url: "{{ url('get/item/information') }}" + '/' + item_id,
			type: 'get',
			data: {
				id: item_id
			},
			dataType: 'json',
			success: function(response){
				if(response.status)
				{
					if(response.status == 'working')
					{
						$('#undermaintenance-form').show(400)
						$('#working-form').hide()
					} 

					if(response.status == 'undermaintenance')
					{
						$('#working-form').show(400)
						$('#undermaintenance-form').hide()
					}

					$('#resolve-item-information').html(`
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

					$('#resolve-item-form').show()
				}
				else if(response.systemunit_id)
				{
					if(response.systemunit.status == 'working')
					{
						$('#undermaintenance-form').show(400)
						$('#working-form').hide()
					} 

					if(response.systemunit.status == 'undermaintenance')
					{
						$('#working-form').show(400)
						$('#undermaintenance-form').hide()
					}

					$('#resolve-item-information').html(`
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
					
					$('#resolve-item-form').show()
				}

				if(response == 'error')
				{

					$('#undermaintenance-form').hide()
					$('#working-form').hide()
					$('#resolve-item-form').hide()
				}

			}
		})
	}

	function setDetailsField()
	{
		_url = "{{ url('maintenance/activity') }}"+ '/' + $('#resolve-activity').val()
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'get',
			url: _url,
			dataType: 'json',
			success: function(response)
			{
				$('#activity-description').html(`
					<div class="alert alert-warning">
						<strong>Details: </strong> ` + response.details + `
					</div>
				`)
			}
		})
	}

})
</script>