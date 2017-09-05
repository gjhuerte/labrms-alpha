{{-- modal --}}
<div class="modal fade" id="transferWorkstationModal" tabindex="-1" role="dialog" aria-labelledby="transferWorkstationModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div class="form-group">
				{{ Form::label('Location') }}
				{{ Form::select('room',['Loading All Rooms'],null,[
					'id' => 'transfer-room',
					'class' => 'form-control'
				]) }}
				</div>	
				<div class="form-group" style="margin-top: 20px;">
				{{ Form::label('Workstation Name') }}
				{{ Form::text('name',null,[
					'id' => 'transfer-name',
					'class' => 'form-control',
					'placeholder' => 'Workstation Name'
				]) }}
				<p class="text-muted">For convention, use WS-<span id="transfer-room-name"></span>-XX</p>
				</div>	
				{{ Form::hidden('items',null,[
					'id' => 'transfer-items'
				]) }}
				<div class="panel panel-info">
					<div class="panel-heading">
						Workstation Information
					</div>
					<ul class="list-group">
					  <li class="list-group-item">Workstation Name: <span id="transfer-name-info"></span></li>
					  <li class="list-group-item">System Unit:  <span id="transfer-systemunit"></span></li>
					  <li class="list-group-item">Monitor:  <span id="transfer-monitor"></span></li>
					  <li class="list-group-item">AVR: <span id="transfer-avr"></span></li>
					  <li class="list-group-item">Keyboard:  <span id="transfer-keyboard"></span></li>
					  <li class="list-group-item">Mouse:  <span id="transfer-mouse"></span></li>
					</ul>
				</div>				
				<div class="form-group">
				<button class="btn btn-block btn-lg btn-primary" data-loading-text="Loading..." type="button" id="modal-transfer">Transfer</button>
				</div>
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>
<script>
$('#transferWorkstationModal').on('show.bs.modal',function(){
	$('#transfer-items').text($('#selected').val())
	$('#transfer-items').val($('#selected').val())

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

			$('#transfer-room').html("");
			$('#transfer-room').append(options);
			$('#transfer-room-name').text($('#transfer-room').val())
		}
	})

	$('#transfer-room').change(function(){
		$('#transfer-room-name').text($('#transfer-room').val())
	})

})
</script>