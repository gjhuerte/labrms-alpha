@extends('layouts.master-blue')
@section('title')
Reservation List
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
{{ HTML::style(asset('css/font-awesome.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body,#edit,#delete{
		display: none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="room-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Reservation List</h3></legend>
			<table class="table table-hover table-condensed table-bordered table-striped" id="reservationTable">
				<thead>
					<th>ID</th>
					<th>Reserved Items</th>
<<<<<<< HEAD
					<th>Reserved By</th>
=======
					<th>Reservee</th>
>>>>>>> origin/0.3
					<th>Faculty in-charge</th>
					<th>Date and Time</th>
					<th>Purpose</th>
					<th>Location</th>
					<th>Status</th>
					<th>Remarks</th>
					<th class="no-sort col-sm-1"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
<<<<<<< HEAD
{{ HTML::script(asset('js/moment.min.js')) }}
=======
	{{ HTML::script(asset('js/moment.min.js')) }}
>>>>>>> origin/0.3
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#reservationTable').DataTable( {
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
	  		"order": [[ '4','desc' ]],
			"processing": true,
	        ajax: "{{ url('reservation') }}",
	        columns: [
	            { data: "id" },
				{ data: function(callback){
					ret_val = "<ul class='list-unstyled'>";
					if(callback.itemprofile.length > 0)
					{ 
						$.each( callback.itemprofile , function(ind,obj){
							ret_val += `<li>` + obj.inventory.itemtype.name + ` - ` + obj.propertynumber + `</li>`
						})
					}

					ret_val += '</ul>'

					return ret_val;
				} },
	            { data: function(callback){
					return callback.user.firstname + ' ' + callback.user.middlename + ' ' + callback.user.lastname
				} },
	            { data: "facultyincharge" },
	            { data: function(callback){
					return moment(callback.timein).format('MMMM DD, YYYY') + " " + moment(callback.timein).format('hh:mm a') + ' - ' + moment(callback.timeout).format('hh:mm a')
				} },
				{ data: "purpose" },
				{ data: "location" },
				{ data: function(callback){
					if(callback.approval == 0)
					{
						return 'pending';
					}
					if(callback.approval == 1)
					{
						return 'approved';
					}
					if(callback.approval == 2)
					{
						return 'disapproved';
					}
				} },
				{ data: 'remark' },
				{ data: function(callback){
					if(callback.approval == 0)
					{
						return `
							<button data-id="`+callback.id+`" class="approve btn btn-xs btn-success"><i class="fa fa-thumbs-o-up fa-2x" aria-hidden="true"></i></button>
							<button data-id="`+callback.id+`" data-reason="`+callback.remark+`"  class="disapprove btn btn-xs btn-danger"><i class="fa fa-thumbs-o-down fa-2x" aria-hidden="true"></i></button>
						`
					}
					if(callback.approval == 1)
					{
						return `
							<button data-id="`+callback.id+`" data-reason="`+callback.remark+`" class="disapprove btn btn-xs btn-danger"><i class="fa fa-thumbs-o-down fa-2x" aria-hidden="true"></i></button>
						`
					}

					return '<p class="text-muted">No Action</p>'
				} }
	        ],
	    } );

	    $('#reservationTable').on('click','.approve',function(){
	    	id = $(this).data('id')
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
					url: '{{ url("reservation") }}' + "/" + id + '/approve',
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Operation Complete','success')
			        		table.ajax.reload();
			        	}else{
							swal('Operation Unsuccessful','Error occurred while processing your request','error')
						}

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

	    $('#reservationTable').on('click','.disapprove',function(){
	    	id = $(this).data('id')
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
					url: '{{ url("reservation") }}' + "/" + id + '/disapprove',
					data: {
						'reason': inputValue
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Operation Complete','success')
			        		table.ajax.reload();
			        	}else{
							swal('Operation Unsuccessful','Error occurred while processing your request','error')
						}

					},
					error: function(){
						swal('Operation Unsuccessful','Error occurred while processing your request','error')
					}
	       		})
	       	})
	    });

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

		$('#page-body').show();
	} );
</script>
@stop
