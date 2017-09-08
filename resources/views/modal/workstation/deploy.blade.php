{{-- modal --}}
<div class="modal fade" id="deployWorkstationModal" tabindex="-1" role="dialog" aria-labelledby="deployWorkstationModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>		
				<div class="form-group">
				{{ Form::label('Location') }}
				{{ Form::select('room',['Loading All Rooms'],null,[
					'id' => 'room',
					'class' => 'form-control'
				]) }}
				</div>	
				<div class="form-group" style="margin-top: 20px;">
				{{ Form::label('Workstation Name') }}
				{{ Form::text('name',null,[
					'id' => 'deploy-name',
					'class' => 'form-control',
					'placeholder' => 'Workstation Name'
				]) }}
				<p class="text-muted">For convention, use WS-<span id="deploy-room-name"></span>-XX</p>
				</div>	
				<div class="panel panel-info">
					<div class="panel-heading">
						Workstation Information
					</div>
					<ul class="list-group">
					  <li class="list-group-item">Workstation Name: <span id="deploy-name-info"></span></li>
					  <li class="list-group-item">System Unit:  <span id="deploy-systemunit"></span></li>
					  <li class="list-group-item">Monitor:  <span id="deploy-monitor"></span></li>
					  <li class="list-group-item">AVR: <span id="deploy-avr"></span></li>
					  <li class="list-group-item">Keyboard:  <span id="deploy-keyboard"></span></li>
					  <li class="list-group-item">Mouse:  <span id="deploy-mouse"></span></li>
					</ul>
				</div>
				{{ Form::hidden('items',null,[
					'id' => 'items'
				]) }}				
				<div class="form-group">
				<button class="btn btn-block btn-lg btn-primary" data-loading-text="Loading..." type="button" id="modal-deploy">Deploy</button>
				</div>
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>
<script>
$('#deployWorkstationModal').on('show.bs.modal',function(){
	$('#items').text($('#selected').val())
	$('#items').val($('#selected').val())

	$.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
		type: 'get',
		url: "{{ route('room.index') }}",
		dataType: 'json',
		success: function(response){
			options = "";
			for(ctr = 0;ctr<response.data.length;ctr++){
				options += `<option value='`+response.data[ctr].name+`'>`+response.data[ctr].name+`</option>'`;
			}

			$('#room').html("");
			$('#room').append(options);
			$('#deploy-room-name').text($('#room').val())
		}
	})

	$('#room').change(function(){
		$('#deploy-room-name').text($('#room').val())
	})

})
</script>