@extends('layouts.master-blue')
@section('title')
Room Information
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/jquery-ui.css')) }}
{{ HTML::style(asset('css/style.css')) }}
<style>

	.modal {
	  text-align: center;
	}

	@media screen and (min-width: 768px) { 
	  .modal:before {
	    display: inline-block;
	    vertical-align: middle;
	    content: " ";
	    height: 100%;
	  }
	}

	.modal-dialog {
	  display: inline-block;
	  text-align: left;
	  vertical-align: middle;
	}

	#page-body{
		display: none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-sm-12">
		<div class="panel panel-default" style="padding:0px 20px">
			<div class="panel-body">
				<legend><h3 class="text-muted">Room {{ $room->name }}</h3></legend>
				<ul class="breadcrumb">
					<li><a href="{{ url('room') }}">Room</a></li>
					<li class="active">{{ $room->name }}</li>
				</ul>
				<ul class="list-unstyled">
					<div class="row">
						<div class="col-sm-6">
							<li><h5 class="text-muted"><label>Name:</label> {{ $room->name }} </h5></li>
							<li><h5 class="text-muted"><label>Category:</label> {{ $room->category }} </h5></li>
							<li><h5 class="text-muted"><label>Description:</label> {{ $room->description }} </h5></li>
						</div>
						<div class="col-sm-6">
							<li><h5 class="text-muted"><label>Inventory List</label></h5></li>
							<ul>

							@if(count($roominventory) > 0)

								@forelse($roominventory as $inventory)

								<li>{{ $inventory->pluck('type')->first() }} ({{ count($inventory) }})</li>

								@empty
									<li>Empty</li>
								@endforelse
							@endif

							</ul>
						</div>
					</div>
				</ul>
				<div>
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">History</a></li>
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="history">
				    	<div class="panel panel-body" style="padding: 10px;">
							<table class="table table-bordered" id="historyTable">
								<thead>
						            <th>ID</th>
						            <th>Name</th>
						            <th>Details</th>
						            <th>Author</th>
						            <th>Status</th>
						        </thead>
							</table>
						</div>
				    </div>
				  </div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/jquery-ui.js')) }}
<script type="text/javascript">
$(document).ready(function(){

	var historyTable = $('#historyTable').DataTable( {
	    language: {
	        searchPlaceholder: "Search..."
	    },
	    order: [[ 0, "desc" ]],
		"processing": true,
        ajax: "{{ url("ticket/room/$room->id") }}",
        columns: [
        	{ data: 'id' },
        	{ data: 'ticketname' },
        	{ data: 'details' },
        	{ data: 'author' },
        	{ data: 'status' }
        ],
    } );

	@if( Session::has("success-message") )
	  swal("Success!","{{ Session::pull('success-message') }}","success");
	@endif
	@if( Session::has("error-message") )
	  swal("Oops...","{{ Session::pull('error-message') }}","error");
	@endif

	$('#page-body').show()
})
</script>
@stop
