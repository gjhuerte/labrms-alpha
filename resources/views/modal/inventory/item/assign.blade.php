<div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="createActivityModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				{{ Form::open(['method'=>'post','route'=>'item.profile.assign']) }}
					<div style="margin-top:20px;margin-bottom:20px;">
						<div class="panel panel-success">
							<div class="panel-heading">
								Item Details
							</div>
							<ul class="list-group">
							  <li class="list-group-item">Property Number:  <span id="assign-propertynumber"></span></li>
							  <li class="list-group-item">Serial ID:  <span id="assign-serialid"></span></li>
							  <li class="list-group-item">Location: <span id="assign-location"></span></li>
							</ul>
						</div>
						{{ Form::hidden('item',null,[
							'id' => 'assign-item',
							'class' => 'form-control'
						]) }}
						<div class="form-group">
						{{ Form::label('Room') }}
						{{ Form::select('room',['Loading all rooms'],null,[
							'id' => 'location',
							'class' => 'form-control'
						]) }}
						</div>
						<button class="btn btn-primary btn-lg btn-block">Assign</button>
					</div>
				{{ Form::close() }}
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>
<script>
$('#assignModal').on('show.bs.modal',function(){
	$.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
		type: 'get',
		url: '{{ url("room") }}',
		dataType: 'json',
		success: function(response){
			options = "";
			for(ctr = 0;ctr<response.data.length;ctr++){
				if(response.data[ctr].name != $('#assign-location').text())
				{
					options += `<option value='`+response.data[ctr].name+`'>`+response.data[ctr].name+`</option>'`;
				}
			}

			$('#location').html("");
			$('#location').append(options);
		}
	})
})
</script>