<div class="modal fade" id="transferTicketModal" tabindex="-1" role="dialog" aria-labelledby="transferTicketModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="text-muted" style="margin-bottom:0px;">Ticket Assignment</h3>
			</div>
			<div class="modal-body">
				{{ Form::open(['method'=>'post','route'=>array('ticket.transfer',csrf_token()),'class'=>'form-horizontal']) }}
					{{ Form::hidden('id',null,[
						'class' => 'form-control',
						'id' => 'transfer-id',
						'readonly',
						'style' => 'background-color: white;'
					]) }}
				<div class="panel panel-primary">
					<div class="panel-heading">
						Ticket Details
					</div>
					<ul class="list-group">
					  <li class="list-group-item">Date:  <span id="transfer-date"></span></li>
					  <li class="list-group-item">Tag:  <span id="transfer-tag"></span></li>
					  <li class="list-group-item">Title: <span id="transfer-title"></span></li>
					  <li class="list-group-item">Details:  <span id="transfer-details"></span></li>
					  <li class="list-group-item">Assigned:  <span id="transfer-assigned"></span></li>
					  <li class="list-group-item">Author:  <span id="transfer-author"></span></li>
					</ul>
				</div>
				<div class="form-group">
					<div class="col-md-12">
					{{ Form::label('Assign to') }}
					{{ Form::select('transferto',['Loading all users ...'],null,[
						'class' => 'form-control',
						'id' => 'transfer-to'
					]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
					{{ Form::label('Comments') }}
					{{ Form::text('comment',null,[
						'class' => 'form-control',
						'id' => 'comment'
					]) }}
					</div>
				</div>
				<input type="hidden" id="transfer-staffid" />
				<div class="form-group">
					<div class="col-md-12">
						<button type="submit" class="btn btn-success btn-lg btn-block">Assign</button>
					</div>
				</div>
				{{ Form::close() }}
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>
<script>
	$('#transferTicketModal').on('show.bs.modal',function(){
		$(document).ready(function(){
			$.ajax({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				type: 'get',
		        url: "{{ route('account.laboratory.staff.all') }}",
		        dataType: 'json',
		        success: function(response){
					options = "";

					for(ctr = 0; ctr < response.data.length; ctr++ )
					{
						name = response.data[ctr].firstname + " " + response.data[ctr].lastname;
						if( response.data[ctr].id != $('#transfer-staffid').val() )
						{
							options += `<option value="`+response.data[ctr].id+`">`+name  +`</option>"`
						}
					}

					if(response.data.length == 0)
					{
						options = `<option>None</option>`
					}

					$('#transfer-to').html("")
					$('#transfer-to').append(options)
				}
			})
		});
	})
</script>