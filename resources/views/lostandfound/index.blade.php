@extends('layouts.master-blue')
@section('title')
Lost And Found
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

	.panel {
		padding: 30px;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="semester-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Lost And Found</h3></legend>
			<p class="text-muted">Note: Other actions will be shown when a row has been selected</p>
			<table class="table table-hover table-condensed table-bordered table-striped" id="lostAndFoundTable">
				<thead>
					<th>ID</th>
					<th>Identifier</th>
					<th>Description</th>
					<th>Date Found</th>
					<th>Days Left</th>
					<th>Claimant</th>
					<th>Date Claimed</th>
					<th>Status</th>
					<th class="no-sort col-sm-1"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#lostAndFoundTable').DataTable( {
	  		select: {
	  			style: 'single'
	  		},
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
	    	"dom": "<'row'<'col-sm-9'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('lostandfound') }}",
	        columns: [
	            { data: "id" },
	            { data: "identifier" },
	            { data: "description" },
	            { data: function(callback){
	            	return moment(callback.datefound).format('MMMM DD, YYYY')
	            } },
	            { data: function(callback){
	            	return moment(moment(callback.datefound).add(31,'days')).diff(moment(),'days')
	            } },
	            { data: "claimant" },
	            { data: function(callback){
	            	if(callback.dateclaimed)
	            		return moment(callback.dateclaimed).format('MMMM DD, YYYY')
	            	else
	            		return ''
	            } },
	            { data: "status" },
				{ data: function(callback){
					if(callback.dateclaimed)
					{

						return '<p class="text-muted">No Action</p>'
					}

					return `
						<button data-id="`+callback.id+`" class="claim btn-block btn btn-xs btn-success">Claim</button>
					`
				} }
	        ],
	    } );

	    $('#lostAndFoundTable').on('click','.claim',function(){
	    	id = $(this).data('id')
	        swal({
				  title: "Claimant Information",
				  text: "Input full name of claimant",
				  type: "input",
				  showCancelButton: true,
				  closeOnConfirm: false,
				  animation: "slide-from-top",
				  inputPlaceholder: "Lastname, Firstname Middlename"
	        },
	        function(inputValue){
				if (inputValue === false) return false;

				if (inputValue === "") {
					swal.showInputError("You need to write claimant name!");
					return false
				}

				$.ajax({
	                headers: {
	                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	                },
					type: 'put',
					url: '{{ url("lostandfound") }}' + "/" + id + '',
					data: {
						'claimant': inputValue,
						'claim': 'claim'
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

	 	$("div.toolbar").html(`
 			<a id="new" class="btn btn-primary btn-flat" style="margin-right:5px;padding: 5px 10px;" href="{{  url("lostandfound/create") }}"><span class="glyphicon glyphicon-plus"></span>  Create</a>
 			@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1)
 			<button id="edit" class="btn btn-default btn-flat" style="margin-right:5px;padding: 6px 10px;"><span class="glyphicon glyphicon-pencil"></span>  Update</button>
 			<button id="delete" class="btn btn-danger btn-flat" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon glyphicon-trash"></span> Remove</button>
 			@endif
		`);

    table
        .on( 'select', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' selection</b> - '+JSON.stringify( rowData )+'</div>' );
 			@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1)
 			if(table.row('.selected').data().status == 'unclaimed')
 			{
	            $('#edit').show()
	            $('#delete').show()	
 			}
 			@endif
        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' <i>de</i>selection</b> - '+JSON.stringify( rowData )+'</div>' );
 			@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1)
            $('#edit').hide()
            $('#delete').hide()
 			@endif
        } );
        
		@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1)
		$('#edit').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = "{{ url('lostandfound') }}" + '/' + table.row('.selected').data().id + '/edit'
					// $('#edit-id').val(table.row('.selected').data().id)
					// $('#edit-name').val(table.row('.selected').data().name)
					// $('#edit-description').val(table.row('.selected').data().description)
					// $('#updateRoomModal').modal('show');
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
			          text: "This item will be removed from lost and found?",
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
							url: '{{ url("lostandfound/") }}' + "/" + table.row('.selected').data().id,
							data: {
								'id': table.row('.selected').data().id
							},
							dataType: 'json',
							success: function(response){
								if(response == 'success'){
									swal('Operation Successful','Item removed','success')
					        		table.row('.selected').remove().draw( false );
					        	}else{
									swal('Operation Unsuccessful','Error occurred while deleting a record','error')
								}
					            $('#edit').hide()
					            $('#delete').hide()
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
		@endif

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
