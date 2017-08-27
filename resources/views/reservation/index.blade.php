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
					<th>Reservee</th>
					<th>Faculty in-charge</th>
					<th>Date and Time</th>
					<th>Purpose</th>
					<th>Location</th>
					<th>Status</th>
					<th>Remarks</th>
					<th class="no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
	{{ HTML::script(asset('js/moment.min.js')) }}
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
			"processing": true,
	        ajax: "{{ url('reservation') }}",
	        columns: [
	            { data: "id" },
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
							<button class="approve btn btn-xs btn-primary">Approve</button>
								<button class="disapprove btn btn-xs btn-danger">Disapprove</button>
						`
					}
					if(callback.approval == 1)
					{
						return `
								<button class="disapprove btn btn-xs btn-danger">Disapprove</button>
						`
					}

					return '<p class="text-muted">No Action</p>'
				} }
	        ],
	    } );

	    $('#reservationTable').on('click','.disapprove',function(){
	        swal({
	          title: "Are you sure?",
	          text: "This reservation will be disapproved. Do you want to continue?",
	          type: "warning",
	          showCancelButton: true,
	          confirmButtonText: "Yes, disapprove it!",
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
					type: 'delete',
					url: '{{ url("room/") }}' + "/" + table.row('.selected').data().id,
					data: {
						'id': table.row('.selected').data().id
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
				});
	          } else {
	            swal("Cancelled", "Operation Cancelled", "error");
	          }
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
