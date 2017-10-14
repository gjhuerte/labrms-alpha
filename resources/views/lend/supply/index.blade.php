@extends('layouts.master-blue')
@section('title')
Supply Lend Log
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body,#delete{
		display: none;
	}

	.panel {
		padding: 30px;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="lend-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Supply Lend Log</h3></legend>
			<p class="text-muted">Note: Other actions will be shown when a row has been selected</p>
			<table class="table table-hover table-condensed table-bordered table-striped" id="lendTable">
				<thead>
					<th>ID</th>
					<th>Supply</th>
					<th>Quantity</th>
					<th>Lend Date</th>
					<th>Returned Date</th>
					<th>Name</th>
					<th>Course Year and Section</th>
					<th>Faculty</th>
					<th>Location</th>
					<th>Status</th>
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
		var table = $('#lendTable').DataTable( {
	  		select: {
	  			style: 'single'
	  		},
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
	    	"dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('lend/supply') }}",
	        columns: [
	            { data: "id" },
	            { data: "supply.brand" },
	            { data: "quantity" },
	            { data: function(callback){
					return moment(callback.timein).format('MMMM DD, YYYY hh:mm a')
	            } },
	            { data: function(callback){
	            	if(callback.timeout)
	            	{
						return moment(callback.timeout).format('MMMM DD, YYYY hh:mm a')
					}
					else
					{
						return null;
					}
	            } },
	            { data: function(callback){
	            	return callback.firstname + ' ' + callback.middlename + ' ' + callback.lastname
	            } },
	            { data: "courseyearsection" },
	            { data: "facultyincharge" },
	            { data: "location" },
	            { data: function(callback){

	            	if(callback.timeout)
	 					return '<p class="text-muted">Returned</p>'
	 				else
		            	return `
	 						<button data-id='` + callback.id + `' class="delete btn btn-sm btn-danger"><span class="glyphicon glyphicon-share-alt"></span> Return</button>
	 					`
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<a id="new" class="btn btn-primary" href="{{  url("lend/supply/create") }}"><span class="glyphicon glyphicon-plus"></span>  Create</a>
		`);

    table
        .on( 'select', function ( e, dt, type, indexes ) {
        	if(table.row('.selected').data().timeout == null)
        	{
            	$('#delete').show()
        	}
        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            $('#delete').hide()
        } );

		$('#edit').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = "{{ url('lend') }}" + '/' + table.row('.selected').data().id + '/edit'
					// $('#edit-id').val(table.row('.selected').data().id)
					// $('#edit-name').val(table.row('.selected').data().name)
					// $('#edit-description').val(table.row('.selected').data().description)
					// $('#updateRoomModal').modal('show');
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		});

	    $('#lendTable').on('click','.delete',function(){

	    	var id = $(this).data('id')

	        swal({
	          title: "Are you sure?",
	          text: "This will record that the supply is returned. Do you want to continue ?",
	          type: "warning",
	          showCancelButton: true,
	          confirmButtonText: "Yes, return it!",
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
					url: '{{ url("lend/supply") }}' + "/" + id,
					data: {
						'id': id
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Supplies Returned','success')
							table.ajax.reload()
  							$('#delete').hide()
			        	}else{
							swal('Operation Unsuccessful','Error occurred! Please reload the page to continue','error')
						}
			            $('#edit').hide()
			            $('#delete').hide()
					},
					error: function(){
						swal('Operation Unsuccessful','Error occurred! Please reload the page to continue','error')
					}
				});
	          } else {
	            swal("Cancelled", "Operation Cancelled", "error");
	          }
	        })
	    });

	    $('#table tbody').on( 'click', 'tr', function () {
	      if ( $(this).hasClass('selected') ) {
	          $(this).removeClass('selected');
	      }
	      else {
	          table.$('tr.selected').removeClass('selected');
	          $(this).addClass('selected');
	      }
	    } );

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
