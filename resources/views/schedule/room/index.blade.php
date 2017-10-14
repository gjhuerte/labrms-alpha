@extends('layouts.master-blue')
@section('title')
Laboratory Schedule
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
{{ HTML::style(asset('css/timetable.css')) }}
<link rel="stylesheet" href="{{ url('css/style.css') }}"  />
<style>
	#page-body,#edit,#delete{
		display: none;
	}
	/*.vip-only{background-color:#1B2C36;border:1px solid #1B2C36}*/
	.timetable .time-entry {
	    background-color: #1B2C36;
	    transition: 200ms background-color;
	    height: 45px;
	    display: block;
	    position: absolute;
	    z-index: 2;
	    padding: 0 10px;
	    white-space: normal;
	    overflow: hidden;
	    color: #fff;
	    padding: 10px;
	    margin: 1px;
	    border: 1px solid #1B2C36;
	    transform-style: preserve-3d;
	}
	.timetable:hover .time-entry:hover {
	    background-color: #1B2C36;
	    transition: 200ms background-color;
	    height: 45px;
	    display: block;
	    position: absolute;
	    z-index: 2;
	    padding: 0 10px;
	    white-space: normal;
	    overflow: hidden;
	    color: #fff;
	    padding: 10px;
	    margin: 1px;
	    border: 1px solid #1B2C36;
	    transform-style: preserve-3d;
	}
/*	.timetable {} 
	.time-entry {}
	.vip-only:hover{background-color:#e5e5e5}*/
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="workstation-info">
		<div class="panel panel-body">
			<legend><h3 class="text-muted">Laboratory Schedules</h3></legend>
			<p class="text-muted">Note: Other actions will be shown when a row has been selected</p>
			<table class="table table-hover table-striped table-bordered table-responsive" id="laboratoryScheduleTable" style="background-color:white;">
				<thead>
					<th>ID</th>
					<th>Day</th>
					<th>Time Start</th>
					<th>Time End</th>
					<th>Subject</th>
					<th>Section</th>
					<th>Academic Year</th>
					<th>Semester</th>
					<th>Faculty-in-charge</th>
				</thead>
			</table>
			<div class="timetable"></div>	
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/timetable.min.js')) }}
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">

	$(document).ready(function() {

		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

	    var table = $('#laboratoryScheduleTable').DataTable( {
			"pageLength": 100,
	  		select: {
	  			style: 'single'
	  		},
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('room/scheduling') }}",
	        columns: [
	            { data: "id" },
	            { data: "day" },
	            { 
	            	data: function(callback){
	            		return moment(callback.timein,'h:m:s').format("h:mm a");
	            	} 
	        	},
	            { 
	            	data: function(callback){
	            		return moment(callback.timeout,'h:m:s').format("h:mm a");
	            	} 
	        	},
	            { data: "subject" },
	            { data: "section" },
	            { data: "academicyear" },
	            { data: "semester" },
	            { data: "faculty" },
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<button id="new" class="btn btn-primary" style="margin-right:5px;padding: 5px 10px;" data-target="reservationItemsAddModal" data-toggle="modal"><span class="glyphicon glyphicon-plus"></span>  Create Schedule</button>
 			<button id="edit" class="btn btn-warning" style="margin-right:5px;padding: 6px 10px;"><span class="glyphicon glyphicon-pencil"></span>  Update</button>
 			<button id="delete" class="btn btn-danger btn-flat" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon glyphicon-trash"></span> Remove</button>
			<div class="btn-group">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="room" style="padding: 5px;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <span id="room-name"></span> <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" id="room-items">
			      @foreach($rooms as $room)
					<li role="presentation">
						<a class="room" data-id='{{ $room->id }}' data-name='{{ $room->name }}'>{{ $room->name }}</a>
					</li>
			    @endforeach
			  </ul>
			</div>
		`);
 
	$('#room-name').text( $('.room:first').text() )


	$('.room').on('click',function(event)
	{
		$('#room-name').text($(this).data('name'))
		table.ajax.url("{{ url('room/scheduling') }}" + '?room=' + $(this).data('id')).load();
	})

    table
        .on( 'select', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' selection</b> - '+JSON.stringify( rowData )+'</div>' );
            $('#edit').show()
            $('#delete').show()
        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' <i>de</i>selection</b> - '+JSON.stringify( rowData )+'</div>' );
            $('#edit').hide()
            $('#delete').hide()
        } );

		$('#new').on('click',function(){
			window.location.href = "{{ url('room/scheduling/create') }}"
		});

		$('#edit').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = "{{ url("room/scheduling") }}" + '/' + table.row('.selected').data().id + '/edit'
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		});

	    $('#delete').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)	
				{
			        swal({
			          title: "Are you sure?",
			          text: "Account will be removed from database?",
			          type: "warning",
			          showCancelButton: true,
			          confirmButtonText: "Yes, delete it!",
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
							url: '{{ url("room/scheduling/") }}' + "/" + table.row('.selected').data().id,
							data: {
								'id': table.row('.selected').data().id
							},
							dataType: 'json',
							success: function(response){
								if(response == 'success'){
									swal('Operation Successful','Activity removed from database','success')
					        		table.row('.selected').remove().draw( false );
					        	}else{
									swal('Operation Unsuccessful','Error occurred while deleting a record','error')
								}
							},
							error: function(){
								swal('Operation Unsuccessful','Error occurred while deleting a record','error')
							}
						});
			          } else {
			            swal("Cancelled", "Operation Cancelled", "error");
			          }
			        })
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
	    });

		$('#page-body').show();

		var timetable = new Timetable();
		timetable.setScope(7, 21); // optional, only whole hours between 0 and 23
		timetable.addLocations(['Monday', 'Tuesday', 'Wednesday', 'Thursday','Friday','Saturday','Sunday']);
		@foreach($roomschedule as $roomschedule)
		timetable.addEvent('{{ $roomschedule->faculty }} | {{ $roomschedule->section }}', '{{ $roomschedule->day }}', new Date({{ Carbon\Carbon::parse($roomschedule->semesterstart)->year }},{{ Carbon\Carbon::parse($roomschedule->semesterstart)->month }},{{ Carbon\Carbon::parse($roomschedule->semesterstart)->day }},{{ Carbon\Carbon::parse($roomschedule->timein)->hour }},{{ Carbon\Carbon::parse($roomschedule->timein)->minute }}), new Date({{ Carbon\Carbon::parse($roomschedule->semesterstart)->year }},{{ Carbon\Carbon::parse($roomschedule->semesterstart)->month }},{{ Carbon\Carbon::parse($roomschedule->semesterstart)->day }},{{ Carbon\Carbon::parse($roomschedule->timeout)->hour }},{{ Carbon\Carbon::parse($roomschedule->timeout)->minute }}), { class: 'vip-only', data: { maxPlayers: 14, gameType: 'Capture the flag' } });
		@endforeach
		var renderer = new Timetable.Renderer(timetable);
		renderer.draw('.timetable'); // any css selector

  });
</script>
@stop
