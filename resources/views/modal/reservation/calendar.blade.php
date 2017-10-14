{{ HTML::style(asset('css/bootstrap-timepicker.min.css')) }}
{{ HTML::script(asset('js/bootstrap-timepicker.min.js')) }}
<div class="modal fade" id="reservationCalendarModal" tabindex="-1" role="dialog" aria-labelledby="reservationCalendarModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<legend><h3 class="text-muted">Search Item Availability</h3></legend>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-sm-12 form-horizontal">
						<div class="col-sm-6">
							<div class="clearfix"></div>
							<div class="row">
								<!-- date of use -->
								<div class="form-group">
									<div class="col-sm-12">
									{{ Form::label('modal_dateofuse','Date of Use',[
					    				'data-language'=>"en"
					    			]) }}
									{{ Form::text('dateofuse',Input::old('dateofuse'),[
										'id' => 'modal_dateofuse',
										'class'=>'form-control',
										'placeholder'=>'MM | DD | YYYY',
										'readonly',
										'style'=>'background-color: #ffffff	'
									]) }}
									</div>
								</div>
								<!-- time started -->
								<div class="form-group">
									<div class="col-sm-12">
									{{ Form::label('time_start','Time started') }}
									{{ Form::text('time_start',Input::old('time_start'),[
										'class'=>'form-control',
										'placeholder'=>'Hour : Min',
										'id' => 'modal_starttime',
										'readonly',
										'style'=>'background-color: #ffffff	'
									]) }}
									</div>
								</div>
								<!-- time_end -->
								<div class="form-group">
									<div class="col-sm-12">
									{{ Form::label('time_end','Time end') }}
									{{ Form::text('time_end',Input::old('time_end'),[
										'class'=>'form-control background-white',
										'placeholder'=>'Hour : Min',
										'id' => 'modal_endtime',
										'readonly',
										'style'=>'background-color: #ffffff	'
									]) }}
									</div>
								</div>
								<!-- Item type -->
								<div class="form-group">
									<div class="col-xs-12">
										{{ Form::label('itemtype','Items') }}
							            {{ Form::select('item',['Empty list'=>'Empty list'],Input::old('items'),[
							              'id' => '_item',
							              'class'=>'form-control'
							            ]) }}
									</div>
								</div>
								<!-- time_end -->
								<div class="form-group">
									<div class="col-sm-12">
										<button type="button" data-loading-text="Searching..."  autocomplete="off" id="search" class="btn btn-primary btn-block">Search</button>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div id="item-count"></div>
							<div id="search-result"></div>
						</div>
					</div>
				</div>
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>
<script>
$('#reservationCalendarModal').on('show.bs.modal',function(){

		$("#modal_dateofuse").datepicker({
			language: 'en',
			showOtherYears: false,
			todayButton: true,
			minDate: new Date(),
			autoClose: true,
			onSelect: function(){
				$('#modal_dateofuse').val(moment($('#modal_dateofuse').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
			}
		});

		$('#modal_starttime').timepicker({ defaultTime: 'current', minuteStep: 10 });

		$('#modal_endtime').timepicker({ defaultTime: 'current', minuteStep: 10 });

		$("#modal_dateofuse").val(moment().add('3','days').format('MMMM DD, YYYY'));

		$('#search').on('click',function(){
			console.log($('modal_starttime').val())
			$.ajax({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				type:'get',
				url: '{{ url("get/reservation/item/count") }}',
				data: {
					'item': $('#_item').val(),
					'dateofuse': $('#modal_dateofuse').val(),
					'time_start': $('#modal_starttime').val(),
					'time_end': $('#modal_endtime').val()
				},	
				dataType: 'json',
				success: function(response){
					$('#item-count').html(``);

					html = `<div class="text-muted">Availability: `;

					if(response.length == 0)
					{
						html += `<p class="text-danger">No more available items</p>`;
					} 
					else
					{

						html += `<p class="text-success">` + response.length + ` item/s available for reservation</p>`;
					}

					html += `</div>`;

					$('#item-count').append(html)
				}
			})

			$.ajax({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				type:'get',
				url: '{{ url("get/reservation/item/available") }}',
				data: {
					'item': $('#_item').val(),
					'dateofuse': $('#modal_dateofuse').val(),
					'time_start': $('#modal_starttime').val(),
					'time_end': $('#modal_endtime').val()
				},	
				dataType: 'json',
				success: function(response){
					$('#search-result').html(``);

					var html = '';

					html = `
						<div class="panel panel-success">
							<div class="panel-heading">Search Result</div>
					`;

					if(response.length == 0)
					{
						html += `
							<div class="panel-body">
								<p class="text-muted text-center">You are free to reserve at this point in time</p>
							</div>
						`;
					} 
					else
					{

						html += `
							<ul class="list-group">
						`;
					}

					$.each(response,function(index,callback){

						html += `
							<li class="list-group-item">
								<h4 class="list-group-item-heading">
									` + moment(callback.timein).format("hh:mm A") + ' - ' + moment(callback.timeout).format("hh:mm A") + `
								</h4>
								<p class="list-group-item-text">
									Name: ` + callback.firstname + ' ' + callback.lastname + `
								</p>
								<p class="list-group-item-text">
									Purpose: ` + callback.purpose + `
								</p>
							</li>
						`;

					})

					html += `
							</ul>
						</div>
					`;

					$('#search-result').append(html)
				},
				complete: function(){

			        var $btn = $('#search').button('loading')
		            $btn.button('reset')
				}
			})
		})

		function error(attr2,message){
			if($('#_endtime').val()){
				if(moment($('#_starttime').val(),'hh:mmA').isBefore(moment($('#_endtime').val(),'hh:mmA'))){
					$('#_request').show(400);
					$('#_time-end-error-message').html(``)
					$('#_time-start-error-message').html(``)
					$('#_time-end-group').removeClass('has-error');
					$('#_time-start-group').removeClass('has-error');
				}else{
					$('#_request').hide(400);
					$(attr2).html(message).show(400)
					$('#_time-end-group').addClass('has-error');
					$('#_time-start-group').addClass('has-error');
				}
			}
		}

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

				$('#_item').html("")
				$('#_item').append(options)
			},
			error: function(){
				$('#_item').html("")
				$('#_item').append("<option value='null'>There are no available items</option>")
			}
		})
})
</script>