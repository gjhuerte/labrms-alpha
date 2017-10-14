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
		<div class="panel">
			<div class="panel-body">
			    <h4 class="line-either-side text-muted">
		      		Lent Items
		      	</h4>
				<div id="lentitems-list">
				</div>
			</div>
        </div> <!-- end of notification tab -->
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/timetable.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {

		$('.ticket').on('mouseup mouseenter',function(){
			$(this).addClass('panel-shadow')
		})

		$('.ticket').on('mouseout mousedown mouseleave',function(){
			$(this).removeClass('panel-shadow')
		})

		$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			type: 'get',
            url: "{{ url('dashboard') }}" + '?ticket=' + 'All',
            dataType: 'json',
            success: function(response){
            	$('#ticket-list').html("")

            	ret_val = '';

            	$.each(response,function(index,callback){
	            	ret_val += `
				    <a class="list-group-item ticket" style="border:none;" href="{{ url('ticket/history') }}/` + callback.id + `">`

				    ret_val += `<div class="col-sm-2">
				    	<img class="img-circle pull-left" style="width: 64px;" src="{{ asset('images/profile/blank-profile-picture-logo.png') }}" />
				    </div>`

					ret_val += `<h4 class="col-sm-10 text-muted">
						<span class=""><strong>` + callback.author + `</strong></span>
						`

					ret_val += `<br />`

					ret_val +=	`<span class="" style="font-size:10px;">`+ moment(callback.date).format('ddd 	MMMM DD, YYYY hh:mm a') + `</span>`

					ret_val += `</h4>`

					ret_val += `<p class="col-sm-12 text-muted text-justify" style="font-size: 17px;margin-top:10px;">`

					if(callback.title == 'Action Taken' ) 
					{

						ret_val += `<span>An action has been taken</span>`

					}else
					{

						ret_val += `<span>A ticket entitled </span>`

						ret_val += `<span><strong> ` + callback.title + '</strong></span>'

						ret_val += `<span> has been created</span>`

					}

					if(callback.tag != 'None')
					{
						ret_val += `<span> for </span>`
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

					ret_val += `<span>. The details of the ticket are as follows: <br /></span>`

					ret_val += `<span>" ` + callback.details + ` ". </span>`

					if(callback.staff_id && callback.tickettype != 'Action Taken')
					{
						ret_val += `<span>Note:<strong> `+callback.staffassigned+`</strong>! You have been assigned to this ticket. </span>`
					}

					ret_val += `</p>`

					ret_val += `<div class=clearfix></div>`

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
            url: "{{ url('dashboard') }}" + '?reservation=' + 'All',
            dataType: 'json',
            success: function(response){
            	$('#reservation-list').html("")

            	ret_val = '';

            	$.each(response.data,function(index,callback){
	            	ret_val += `
				    <a href="{{ url('reservation') }}` + '/' +callback.id + `" class="list-group-item" style="border:none;">
				      <h4 class="list-group-item-heading">`

					ret_val += `{{ Form::open([ 'method' => 'post', 'route' => 'reservation.claim','class' => 'form-inline' ]) }}`	

				    ret_val += `
				    		<span class="text-muted">` + callback.user.firstname + ' ' + callback.user.lastname + `</span>
				    `
				    const start = new Date(callback.timein);
					const end  = new Date(callback.timeout);
					const curDate  = moment();
					if(curDate.isBetween(start,end))
	            	{
	            		ret_val += `
							<input type="hidden" value="`+callback.id+`" name="id" />
							<button type="submit" class="label label-warning" style="border:none;"><marquee>Claim Now!</marquee></button>
						`
	            	}
	            	else
	            	{

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
					}

					ret_val += `</h4>`


					ret_val += `{{ Form::close() }}`

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

					ret_val += ``;

					ret_val += `
						</a>
						<hr />
					`

            	})

            	if(response.data.length == 0)
            	{
            		ret_val = `<p style="font-size:10px" class="text-center text-muted"> No approved reservations three working days from now </p>`
            	}

				$('#reservation-list').append(ret_val)
			}
		})

		$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			type: 'get',
            url: "{{ url('dashboard') }}" + '?lentitems=' + 'All',
            dataType: 'json',
            success:function(response){
            	$('#lentitems-list').html("")

            	ret_val = '';

            	$.each(response.data,function(index,callback){
	            	ret_val += `
				    <a href="{{ url('lend') }}` + '/' +callback.id + `" class="list-group-item" style="border:none;">
				      <h4 class="list-group-item-heading">`

				    ret_val += `
				    		<span class="text-muted">` + callback.user.firstname + ' ' + callback.user.lastname + `</span>
				    `
				    const start = new Date(callback.timein);
					const end  = new Date(callback.timeout);
					const curDate  = moment();
					if(curDate.isBetween(start,end))
	            	{
	            		ret_val += `
							<input type="hidden" value="`+callback.id+`" name="id" />
							<button type="submit" class="label label-warning" style="border:none;"><marquee>Claim Now!</marquee></button>
						`
	            	}
	            	else
	            	{

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
					}

					ret_val += `</h4>`


					ret_val += `{{ Form::close() }}`

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

					ret_val += ``;

					ret_val += `
						</a>
						<hr />
					`

            	})

            	if(response.data.length == 0)
            	{
            		ret_val = `<p style="font-size:10px" class="text-center text-muted"> No items are being lent </p>`
            	}

				$('#lentitems-list').append(ret_val)
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
