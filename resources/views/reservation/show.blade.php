@extends('layouts.master-blue')
@section('title')
Reservation
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/font-awesome.min.css')) }}
<style>

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

	.container-fluid > p , .container-fluid > h4 , .container-fluid > li {
		font-size: 18px;
	}

</style>
@stop
@section('content')
<div class="container-fluid">
	<div class="col-md-offset-3 col-md-6">
	@if(isset($reservation))
		<div class="panel panel-body">
			<h4 class="line-either-side text-muted" style="font-size: 24px;">Notice of 
		      		@if($reservation->approval == 1)
		      		Approved Reservation
		      		@elseif($reservation->approval == 2)
		      		Disapproval
		      		@elseif($reservation->approval == 0)
		      		Reservation
		      		@endif
      		</h4>
      		<h4 class="text-justify text-muted" style="font-size: 22px;">
      		<span class="pull-left">Hi <strong> {{ $reservation->user->firstname }} </strong>!</span>
      		@if($reservation->approval == 0)
      		<span class="pull-right">
							<button data-id="`{{ $reservation->id }}`" id="approve" class="btn btn-xs btn-success"><i class="fa fa-thumbs-o-up fa-2x" aria-hidden="true"></i></button>
							<button id="disapprove" data-id="`{{ $reservation->id }}`" data-reason="`{{ $reservation->remark }}"  class="btn btn-xs btn-danger"><i class="fa fa-thumbs-o-down fa-2x" aria-hidden="true"></i></button>
			</span>
			@endif
      		</h4>
      		<div class="clearfix"></div>
      		<br />
      		<p class="text-muted">
      			We have received your reservation request and we would like to notify you that your request has been 
		      		@if($reservation->approval == 1)
		      		<span class="label label-success">Approved</span>. You may claim your item on {{ Carbon\Carbon::parse($reservation->timein)->toFormattedDateString() }} from {{ Carbon\Carbon::parse($reservation->timein)->format('h:i A') }} to {{ Carbon\Carbon::parse($reservation->timeout)->format('h:i A') }}.
		      		@elseif($reservation->approval == 2)
		      		<span class="label label-danger">Disapproved</span> 
      				due to the following reasons:
		      		@elseif($reservation->approval == 0)
		      		still <span class="label label-info">Undecided</span>.
		      		@endif
      		</p>
      		@if($reservation->approval == 2)
      		<p class="text-muted">
      			<blockquote>
				  <p>{{ $reservation->remark }}</p>
				  <footer>Laboratory Staff</footer>
				</blockquote>
      		</p>
      		@endif
      		<p class="text-muted">
      			Reserved Items:
  				<ul class="list-unstyled text-muted">
      				@foreach($reservation->itemprofile as $item)
  					<li> - {{ $item->inventory->itemtype->name }} </li>
  					@endforeach
  				</ul>
      		</p>  
      		<p class="text-muted">
      			Thank you for your kind consideration!
      		</p> 
      		<p class="text-muted col-md-offset-8 col-md-4 text-left">
      			Sincerely Yours,
      			<br />
      			The LabRMS Team
      		</p> 
      		<br />
      		<br />
      		<br />
      		<br />
      		<p class="text-muted" style="font-size: 12px;">
      			Reservation Details:
      			Name : {{ $reservation->user->firstname }} {{ $reservation->user->lastname }} <br />
      			Date: {{ Carbon\Carbon::parse($reservation->timein)->toFormattedDateString() }} <br />
      			Time Start : {{ Carbon\Carbon::parse($reservation->timein)->format('h:m a') }} <br />
      			Time End : {{ Carbon\Carbon::parse($reservation->timeout)->format('h:m a') }} <br />
      		@if($reservation->user->accesslevel == 4)
      			Faculty in-charge : {{ $reservation->facultyincharge }} <br />
      		</p> 
      		@endif
			</div>
		</div>
	@endif
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
@if($reservation->approval == 0)
	    $('#approve').on('click',function(){
			swal({
			  title: "Are you sure?",
			  text: "Do you really want to approve this reservation?",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonColor: "#DD6B55",
			  confirmButtonText: "Yes, approve it!",
			  cancelButtonText: "No, cancel it!",
			  closeOnConfirm: false,
			  closeOnCancel: false
			},
			function(isConfirm){
			  if (isConfirm) {
				$.ajax({
	                headers: {
	                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	                },
					type: 'post',
					url: '{{ url("reservation/$reservation->id/approve") }}',
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Operation Complete','success')
			        	}else{
							swal('Operation Unsuccessful','Error occurred while processing your request','error')
						}
		        		location.reload();

					},
					error: function(){
						swal('Operation Unsuccessful','Error occurred while processing your request','error')
					}
	       		})
			  } else {
			    swal("Cancelled", "Request Cancelled", "error");
			  }
			});
	    });

	    $('#disapprove').on('click',function(){
	        swal({
				  title: "Remarks!",
				  text: "Input reason for disapproving the reservation",
				  type: "input",
				  showCancelButton: true,
				  closeOnConfirm: false,
				  animation: "slide-from-top",
				  inputPlaceholder: "Write something"
	        },
	        function(inputValue){
				if (inputValue === false) return false;

				if (inputValue === "") {
					swal.showInputError("You need to write something!");
					return false
				}

				$.ajax({
	                headers: {
	                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	                },
					type: 'post',
					url: '{{ url("reservation/$reservation->id/disapprove") }}',
					data: {
						'reason': inputValue
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Operation Complete','success')
			        	}else{
							swal('Operation Unsuccessful','Error occurred while processing your request','error')
						}
			        		location.reload();

					},
					error: function(){
						swal('Operation Unsuccessful','Error occurred while processing your request','error')
					}
	       		})
	       	})
	    });
	@endif
	@if( Session::has("success-message") )
	  swal("Success!","{{ Session::pull('success-message') }}","success");
	@endif
	@if( Session::has("error-message") )
	  swal("Oops...","{{ Session::pull('error-message') }}","error");
	@endif

</script>
@stop
