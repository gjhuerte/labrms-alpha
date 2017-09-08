@extends('layouts.master-blue')
@section('title')
Dashboard
@stop
@section('navbar')
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/timetable.css')) }}
<style>
  .panel-shadow{
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
</style>
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
				<div id="ticket-list">
				</div>
			</div> 
        </div> <!-- end of notification tab -->
	</div>
</div>
<!-- <div class="container-fluid">
	<div class="col-md-3">
		<ul class="list-group panel panel-default">
			<div class="panel-heading">
			    Notification  <span class="label label-primary" id="notification-count" val=0>0</span> <p class="text-success pull-right">Active</p>
			</div>
			<li class="list-group-item">
			List Item
			</li>
		</ul>
	</div>
	<div class="col-md-6">
		<ul class="panel panel-default">
			<div class="panel-body" id="content">
            <button class="btn btn-info" data-toggle="modal" data-target="#generateTicketModal"><span class="glyphicon glyphicon-share-alt"></span> View all</button>
            <button class="btn btn-default" data-toggle="modal" data-target="#generateTicketModal"><span class="glyphicon glyphicon-share-alt"></span> Transfer Ticket</button>
    		    <button class="btn btn-primary" data-toggle="modal" data-target="#generateTicketModal"><span class="glyphicon glyphicon-plus"></span> Generate Ticket</button>
			</div>
		</ul>
	</div>
</div>

-->
{{-- <div class="container-fluid">
	<div class="panel panel-default">
		<div class="panel-body">
			<legend><h3 class="text-muted">Laboratory Schedule</h3></legend>
			<div class="timetable"></div>
		</div>
	</div>
</div> --}}
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/timetable.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {

		$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			type: 'get',
            url: "{{ url('dashboard') }}" + '?ticket=' + 'all',
            dataType: 'json',
            success: function(response){
            	$('#ticket-list').html("")

            	$.each(response.data,function(index,callback){
	            	ret_val = `
				    <a href="{{ url('ticket/history') }}/` + callback.id + `" class="list-group-item">`


					ret_val += `<p class="list-group-item-text">Date:` + 
						moment(callback.created_at).format('MMMM DD, YYYY') + `</p>`

					ret_val += `<p class="list-group-item-text">Tag:` + callback.tag + `</p>`

					ret_val += `<p class="list-group-item-text">Title:` + callback.title + `</p>`

					ret_val += `<p class="list-group-item-text">Details:` + callback.details + `</p>`

					ret_val += `<p class="list-group-item-text">Author:` + callback.author + `</p>`

					ret_val += `
						</a>
					`

					$('#ticket-list').append(ret_val)
            	})

    //         	ret_val = `
				// 	<nav>
				// 	  <ul class="pagination">
				// 	    <li>
				// 	      <a href="` + response.prev_page_url + `" aria-label="Previous">
				// 	        <span aria-hidden="true">&laquo;</span>
				// 	      </a>
				// 	    </li>
				// `;

				// for( ctr = response.current_page ; ctr <= response.last_page ; ctr++ )
				// {

	   //          	ret_val += `
				// 		    <li><a href="` + response.path + `?page=` + ctr + `">` + response.current_page + `</a></li>
				// 	`;

				// }


	   //      	ret_val += `
				// 	      <a href="` + response.next_page_url + `" aria-label="Next">
				// 	        <span aria-hidden="true">&raquo;</span>
				// 	      </a>
				// 	    </li>
				// 	  </ul>
				// 	</nav>
				// `;

    //         	$('#ticket-list').append(ret_val)
			}
		})

		$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			type: 'get',
            url: "{{ url('dashboard') }}" + '?reservation=' + 'all',
            dataType: 'json',
            success: function(response){
            	$('#reservation-list').html("")
            	$.each(response.data,function(index,callback){
	            	ret_val = `
				    <a href="#" class="list-group-item">
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

		// setInterval(function(){
		// 	var count = $('#notification-count').val();
		// 	count++;
		// 	$('#notification-count').val(count);
		// 	$('#notification-count').html(count);
		// },1000);

		var timetable = new Timetable();
		timetable.setScope(7, 21); // optional, only whole hours between 0 and 23
		timetable.addLocations(['Monday', 'Tuesday', 'Wednesday', 'Thursday','Friday','Saturday','Sunday']);
		// timetable.addEvent('Frankadelic', 'Nile', new Date(2015,7,17,10,45), new Date(2015,7,17,12,30));
		var renderer = new Timetable.Renderer(timetable);
		renderer.draw('.timetable'); // any css selector
	});
</script>
@stop
