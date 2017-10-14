@extends('layouts.master-blue')
@section('title')
Inventory
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style-include')
{{ HTML::style(asset('css/jquery-ui.css')) }}
{{ HTML::style(asset('css/jquery.sidr.light.min.css')) }}
{{ HTML::style(asset('css/sidr-style.min.css')) }}
@stop
@section('script-include')
{{ HTML::script(asset('js/jquery.sidr.min.js')) }}
{{ HTML::script(asset('js/jquery.hideseek.min.js')) }}
<script src="{{ asset('js/jQuery.succinct.min.js') }}"></script>
@stop
@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>

	#page-body,#profile,#view{
		display: none;
	}

	a > hover{
		text-decoration: none;
	}

	th , tbody{
		text-align: center;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	@include('modal.inventory.create')
	@include('modal.inventory.item.profile.create')
	<div class="col-md-12">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Inventory</h3></legend>
			<p class="text-muted">Note: Other actions will be shown when a row has been selected</p>
			<table class="table table-hover table-striped table-bordered table-condensed" id="inventoryTable">
				<thead>
					<th>ID</th>
					<th>Model</th>
					<th>Brand</th>
					<th>Type</th>
					<th>Details</th>
					<th>Warranty</th>
					<th>Unit</th>
					<th>Quantity</th>
					<th>Unprofiled</th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/jquery-ui.js')) }}
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

	    var table = $('#inventoryTable').DataTable({
			"pageLength": 100,
			select: {
				style: 'single'
			},
			language: {
					searchPlaceholder: "Search..."
			},
<<<<<<< HEAD
			"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f><'search-bar'>>" +
=======
			"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
>>>>>>> origin/0.3
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
					ajax: "{{ url('inventory/item') }}",
					columns: [
							{ data: "id" },
							{ data: "model" },
							{ data: "brand" },
							{ data: "itemtype.name" },
							{ data: "details" },
							{ data: "warranty" },
							{ data: "unit" },
							{ data: "quantity" },
							{ data: function(callback){
								return callback.quantity - callback.profileditems
							} }
					],
	    });

	 	$("div.toolbar").html(`
				<button id="new" class="btn btn-md btn-primary">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					<span id="nav-text">Add</span>
				</button>
				<button id="profile" class="btn btn-success " type="button">
					<span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>
					<span class="hidden-sm hidden-xs">Profile Items</span>
				</button>
				<button id="view" class="btn btn-default" type="button">
					<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
					<span class="hidden-sm hidden-xs">View Profiled Items</span>
				</button>
		`);

	 	$('div.search-bar').html(`
	 		<div class="col-sm-12">
				<a href="{{ url('inventory/item/search') }}" class="pull-right" style="font-size:10px;text-decoration:none;">Advance Search</a>
			</div>
 		`)
 
    table
        .on( 'select', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' selection</b> - '+JSON.stringify( rowData )+'</div>' );
            $('#profile').show()
            $('#view').show()
        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' <i>de</i>selection</b> - '+JSON.stringify( rowData )+'</div>' );
            $('#profile').hide()
            $('#view').hide()
        } );

	    $('[data-toggle="popover"]').popover();

	    $('.truncate').succinct({
	        size: 20
	    });

	    $('#new').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = "{{ url('inventory/item/create') }}"
											 + '?brand=' + table.row('.selected').data().brand
											 + '&' +'model=' + table.row('.selected').data().model
											 + '&' +'itemtype=' + table.row('.selected').data().itemtype.name
				}
			}catch( error ){
				window.location.href = "{{ url('inventory/item/create') }}"
			}
	    })

	    $('#edit').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = "{{ url('inventory/item') }}" + '/' + table.row('.selected').data().id + '/edit'
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
	    })

		$('#view').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = "{{ url('item/profile') }}" + '/' + table.row('.selected').data().id
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		})

	    $('#profile').on('click',function(){
				try{
					if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
					{
			    	// $('#inventory_id').text($(this).data('id'))
			    	// $('#inventory_id').val($(this).data('id'))
			    	// $('#createItemProfileModal').modal('show');
			    	window.location.href = "{{ url('item/profile/create?id=') }}" + table.row('.selected').data().id
					}
				}catch( error ){
					swal('Oops..','You must choose atleast 1 row','error');
				}
	    })

		$('#page-body').show();
	} );
</script>
@stop
