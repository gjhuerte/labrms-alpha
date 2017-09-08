@extends('layouts.master-blue')
@section('title')
Dashboard
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('content')
<div class="container-fluid">
	<div class="col-md-3" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-info">
			<div class="panel-heading" role="tab" id="headingOne">
				<div class="panel-title">
				    <a role="button">
				      Reservation list
				    </a>
				</div>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne" style="margin-bottom: 0;padding-bottom:0;">
				<div id="reservation-list">
				</div>
			</div>
        </div> <!-- end of notification tab -->
	</div>
	<div class=" col-md-6">
		<div class="col-sm-12 panel panel-body"  id='calendar'>
			<div></div>
		</div>
	</div>
	<div class="col-md-3" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-primary">
			<div class="panel-heading" role="tab" id="headingOne">
				<div class="panel-title">
				    <a role="button">
				      Ticket list
				    </a>
				</div>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne" style="margin-bottom: 0;padding-bottom:0;">
				<div id="complaints">
				</div>
			</div> 
        </div> <!-- end of notification tab -->
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">	
$(document).ready(function() {
		$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			type: 'get',
            url: "{{ url('dashboard') }}" + '?reservation=' + 'User',
            dataType: 'json',
            success: function(response){
            	$('#reservation-list').html("")
            	$.each(response.data,function(index,callback){
	            	ret_val = `
				    <a href="{{ url('reservation') }}/` + callback.id + `" class="list-group-item">
				      <h4 class="list-group-item-heading">`

			      	if(callback.approval == 0) 
						ret_val += `
							<p class="text-warning">
								<span class="label label-info">Undecided</span>
							</p>
						`
					if(callback.approval == 1) 
						ret_val += `
							<p class="text-success">
					  		<span class="label label-success">Approved</span>
							</p>
						`
					if(callback.approval == 2) 
						ret_val += `
							<p class="text-danger">
					  		<span class="label label-danger">Disapproved</span>
							</p>
						`
						
					ret_val += `</h4>`

					if(callback.itemprofile)
					{
						if(callback.itemprofile.length > 0)
						{
							ret_val += `<ul class="list-unstyled"><label>Item List</label>`
					  		$.each(callback.itemprofile,function(index,itemprofile){
					  			ret_val +=  `
					  				<li class="list-group-item-text">` + itemprofile.inventory.itemtype.name + `-` + itemprofile.propertynumber + `</li>`
					  		})
					  		ret_val += `</ul>`
						}
					}

					ret_val += `<p class="list-group-item-text">Date:` + 
						moment(callback.timein).format('MMMM DD, YYYY') + `</p>`

					ret_val += `<p class="list-group-item-text">Time:` + moment(callback.timein).format('hh:mm a') + ' - ' + moment(callback.timeout).format('hh:mm a') + `</p>`

					ret_val += `<p class="list-group-item-text">Purpose:` + callback.purpose + `</p>`

					if(callback.approval == 2) 
						ret_val += `<p class="list-group-item-text">Remarks:` + callback.remark + `</p>`

					ret_val += `
						</a>
					`

					$('#reservation-list').append(ret_val)
            	})
			}
		})

		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif
	});
</script>
@stop