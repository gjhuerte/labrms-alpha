@extends('layouts.master-blue')
@section('title')
Items Profile
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
		<legend><h3 class="text-muted">Items Profile</h3></legend>
		<table class="table table-hover table-condensed table-bordered table-responsive" id="roomTable">
			<thead>
				<th>ID</th>
				<th>Property Number</th>
				<th>Serial Number</th>
				<th>Location</th>
				<th>Brand</th>
				<th>Model</th>
				<th>Item Type</th>
				<th>Date Received</th>
				<th>Status</th>
				<th class="no-sort"></th>
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
					"processing": true,
			        ajax: "{{ url('item/profile') }}",
			  		select: {
			  			style: 'single'
			  		},
			    	columnDefs:[
						{ targets: 'no-sort', orderable: false },
			    	],
				    language: {
				        searchPlaceholder: "Search..."
				    },
			    	"dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
								    "<'row'<'col-sm-12'tr>>" +
								    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			        columns: [
			            { data: "id" },
			            { data: "propertynumber" },
			            { data: "serialnumber" },
			            { data: "location" },
			            { data: "inventory.brand" },
			            { data: "inventory.model" },
			            { data: "inventory.itemtype.name" },
			            { data: function(callback){
								if(moment(callback.datereceived).isValid()){
									return moment(callback.datereceived).format('MMM DD, YYYY');
								}else{
									return moment().format('MMM DD, YYYY');
								}
							}
						},
			            { data: "status" },
				        { data: function(callback){
				        	return "<a href='{{ url("item/profile/history") }}" + '/' +  callback.id + "' class='btn btn-sm btn-default btn-block'>View History</a>"
				        } }
			        ],
			    } );

			 	$("div.toolbar").html(`
				  	Item type:
					<div class="btn-group">
					  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="itemtype" style="padding:10px;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <span id="itemtype-name"></span> <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu" id="itemtypeitems">
							<li role="presentation" value='All'>
								<a class="itemtype" data-id='All' data-name='All'>All</a>
							</li>
					      @foreach($itemtype as $itemtype)
							<li role="presentation" value='{{ $itemtype->name }}'>
								<a class="itemtype" data-id='{{ $itemtype->id }}' data-name='{{ $itemtype->name }}'>{{ $itemtype->name }}</a>
							</li>
					    @endforeach
					  </ul>
					</div>
				  	Status:
					<div class="btn-group">
					  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="status" style="padding:10px;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <span id="status-name"></span> <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu" id="statusitems">
						<li role="presentation" value='working'>
							<a class="status" data-name='working'>working</a>
						</li>
						<li role="presentation" value='undermaintenance'>
							<a class="status" data-name='undermaintenance'>undermaintenance</a>
						</li>
						<li role="presentation" value='condemn'>
							<a class="status" data-name='condemn'>condemned</a>
						</li>
					  </ul>
					</div>
				`);

			$('#itemtype-name').text( $('.itemtype:first').text() )
			$('#status-name').text( $('.status:first').text() )

		}

		$('.itemtype').on('click',function(){
			$('#itemtype-name').text($(this).data('name'));
			setFilter()
		})

		$('.status').on('click',function(){
			$('#status-name').text($(this).data('name'));
			setFilter()
		})

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

		function setFilter()
		{

			name = $('#itemtype-name').text()
			status = $('#status-name').text()
			url = "{{ url('item/profile') }}" + '?id=' + name + '&&status=' + status
			table.ajax.url( url ).load();
		}

		$('#page-body').show();
	} );
</script>
@stop
