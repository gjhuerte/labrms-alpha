@extends('layouts.master-blue')
@section('title')
Room Inventory
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body{
		display: none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="" style="background-color: white;padding: 20px;">
		<legend><h3 class="text-muted">Room Inventory</h3></legend>
		<div id="notif-board">
		@if(isset($ticket_count))
			@if( $ticket_count > 0 )
			<div class="alert alert-warning">
				<strong>Warning!</strong> Item with the following property number {{ $ticket_link }} has accumulated {{ isset($ticket_count) ? $ticket_count : 0  }} complaint/s as of {{ Carbon\Carbon::now()->toFormattedDateString() }} . 
			</div>
			@endif
		@endif
		</div>
		<table class="table table-hover table-condensed table-bordered table-responsive" id="roomTable">
			<thead>
				<th>Item Model</th>
				<th>Item Brand</th>
				<th>Property Number</th>
				<th>Date Assigned</th>
			</thead>
		</table>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {

		init(1);

		function init(data)
		{

			table = $('#roomTable').DataTable({
					'pageLength': 10,
					"processing": true,
			        ajax: "{{ url('get/room/inventory/details') }}" + '/' + data,
			  		select: {
			  			style: 'single'
			  		},
				    language: {
				        searchPlaceholder: "Search..."
				    },
			    	"dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
								    "<'row'<'col-sm-12'tr>>" +
								    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			        columns: [
			            { data: "model" },
			            { data: "brand" },
			            { data: "propertynumber" },
			            { data: function(){
								if(moment("created_at").isValid()){
									return moment('created_at','MMM-DD-YYYY');
								}else{
									return moment().format('MMM DD, YYYY');
								}
							}
						},
			        ],
			    } );

			 	$("div.toolbar").html(`
					<div class="btn-group">
					  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="room" style="padding:10px;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <span id="room-name"></span> <span class="caret"></span>
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

		}

		$('.room').on('click',function(event)
		{
			roomname = $(this).data('name')
			id = $(this).data('id')
			$('#room-name').text(roomname)
			table.ajax.url("{{ url('get/room/inventory/details') }}" + '/' + id).load();

			url = "{{ url('inventory/room') }}"
			$.ajax({
				headers: {
				  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					'notif': roomname
				},
				type:'get',
				url: url,
				dataType: 'json',
				success: function(callback){
					link = callback.ticket_link;
					count = callback.ticket_count;
					date = moment().format("MMMM DD, YYYY")
					if(count > 0)
					{

						$('#notif-board').html(`
							<div class="alert alert-warning">
								<strong>Warning!</strong> Item with the following property number `+link+` has accumulated `+count+` complaint/s as of `+date+` . 
							</div>
						`)
					}
					else
					{
						$('#notif-board').html(``)
					}
				}

			})
		})

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
