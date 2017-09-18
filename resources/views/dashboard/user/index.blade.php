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
		padding: 10px;
	   -moz-box-shadow: 3px 3px 4px #e5e5e5; 
	   -webkit-box-shadow: 3px 3px 4px #e5e5e5; 
	   box-shadow: 3px 3px 4px #e5e5e5; 
	}

	.panel {
		border-radius: 0px;
	}

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

</style>
@stop
@section('content')
<div class="container-fluid">
	<div class="col-md-3" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel">
			<div class="panel-body">
			    <h4 class="line-either-side text-muted">
		      		Reservation
		      	</h4>
				<div id="reservation-list">
				</div>
			</div>
        </div> <!-- end of notification tab -->
	</div>
	<div class=" col-md-6">
		<div class="panel panel-primary">
			<div class="panel-body">
			    <h4 class="line-either-side text-muted">
		      		Activity
		      	</h4>
				<div id="ticket-list">
				</div>
			</div>
        </div> <!-- end of notification tab -->
	</div>
	<div class="col-md-3" id="accordion" role="tablist" aria-multiselectable="true">
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
            url: "{{ url('dashboard') }}" + '?ticket=' + 'User',
            dataType: 'json',
            success: function(response){
            	$('#ticket-list').html("")

            	ret_val = '';

            	$.each(response,function(index,callback){
	            	ret_val += `
				    <a class="list-group-item panel-shadow" style="border:none;" href="{{ url('ticket/history') }}/` + callback.id + `">`

					ret_val += `<h4 class="text-muted">` + callback.id + `.  ` + callback.title 

					if(callback.tag != 'None')
					{
						if(callback.tag.indexOf('PC') !== -1 || callback.tag.indexOf('Item') !== -1)
						{
							if(callback.tag.indexOf('PC') !== -1)
							{
								ret_val += ` <span class="label label-primary">` + callback.tag + `</span>`
							}

							if(callback.tag.indexOf('Item') !== -1)
							{
								ret_val += ` <span class="label label-info">` + callback.tag + `</span>`
							}
						}
						else
						{
							ret_val += ` <span class="label label-success">` + callback.tag + `</span>`
						}
					}

					ret_val += `</h4>`

					ret_val += `<p style="font-size:11px;" class="text-muted">
						<span class="pull-left">`+ 
						moment(callback.created_at).format('MMMM DD, YYYY hh:mm a') + `</span>`

					ret_val += `<span class="pull-right">` + callback.author + `</span></p><div class=clearfix></div>`

					ret_val += `<p class="well text-muted text-justify">` + callback.details + `</p>`

					// ret_val += `
					// 	<div class="form-group clearfix">
					// 		<button class="btn btn-sm btn-success pull-left">Create an Action</button>
					// 		<button class="btn btn-sm btn-danger pull-right">Close</button>
					// 	</div>
					// `

					ret_val += `
						</a><hr />
					`

            	})

            	if(response.length == 0)
            	{
            		ret_val = `<p style="font-size:10px" class="text-center text-muted"> No tickets generated this day </p>`
            	}

				$('#ticket-list').append(ret_val)

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
            url: "{{ url('dashboard') }}" + '?reservation=' + 'User',
            dataType: 'json',
            success: function(response){
            	$('#reservation-list').html("")

            	ret_val = '';

            	$.each(response.data,function(index,callback){
	            	ret_val += `
				    <a href="{{ url('reservation') }}` + '/' +callback.id + `" class="list-group-item" style="border:none;">
				      <h4 class="list-group-item-heading">`

				    ret_val += `
				    		<span class="text-muted">` + callback.user.firstname + ' ' + callback.user.lastname + `</span>
				    `

			      	if(callback.approval == 0) 
						ret_val += `
								<span class="label label-info">Undecided</span>
						`
					if(callback.approval == 1) 
						ret_val += `
					  		<span class="label label-success">Approved</span>
						`
					if(callback.approval == 2) 
						ret_val += `
					  		<span class="label label-danger">Disapproved</span>
						`
						
					ret_val += `</h4>`

					ret_val += `<p class="text-muted" style="font-size: 11px;">` + 
						moment(callback.timein).format('MMMM DD, YYYY') 

					ret_val += ` | ` + moment(callback.timein).format('hh:mm a') + ' - ' + moment(callback.timeout).format('hh:mm a') + `</p>`

					if(callback.itemprofile)
					{
						if(callback.itemprofile.length > 0)
						{
							ret_val += `<ul class="list-unstyled">`
					  		$.each(callback.itemprofile,function(index,itemprofile){
					  			ret_val +=  `
					  				<li class="list-group-item-text"> - ` + itemprofile.inventory.itemtype.name + `</li>`
					  		})
					  		ret_val += `</ul>`
						}
					}

					ret_val += `
						</a>
					`

            	})

            	if(response.data.length == 0)
            	{
            		ret_val = `<p style="font-size:10px" class="text-center text-muted"> No approved reservations three working days from now </p>`
            	}

				$('#reservation-list').append(ret_val)
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

		// var timetable = new Timetable();
		// timetable.setScope(7, 21); // optional, only whole hours between 0 and 23
		// timetable.addLocations(['Monday', 'Tuesday', 'Wednesday', 'Thursday','Friday','Saturday','Sunday']);
		// // timetable.addEvent('Frankadelic', 'Nile', new Date(2015,7,17,10,45), new Date(2015,7,17,12,30));
		// var renderer = new Timetable.Renderer(timetable);
		// renderer.draw('.timetable'); // any css selector
	});
</script>
@stop
