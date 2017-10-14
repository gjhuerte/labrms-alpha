@extends('layouts.master-blue')
@section('title')
Tickets
@stop
@section('navbar')
@include('layouts.navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
{{ HTML::style(asset('css/font-awesome.min.css')) }}
<link rel="stylesheet" href="{{ url('css/style.css') }}"  />
<style>
	#page-body,#assign,#resolve,#close,#reopen{
		display: none;
	}

	.toolbar {
    	float:left;
	}

	textarea{
		resize:none;
		overflow-y:hidden;
	}

	.overlay{
		margin-bottom: 10px;
		width:fit-content;
	}

</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
@include('modal.ticket.transfer')
@include('modal.ticket.resolve')
@endif
	<div class="col-md-12" id="workstation-info">
		<div class="panel panel-body table-responsive" style="padding: 25px 30px;">
			<legend class="text-muted">Tickets</legend>
			 <!--Counter Section-->
	        <section id="counter_two" class="counter_two col-sm-12">
	            <div class="overlay" style="border: none;">
	                <div class="container">
	                    <div class="row">
	                        <div class="main_counter_two sections text-center text-muted">
	                            <div>
	                                <div class="row">
	                                    <div class="col-sm-3 col-xs-12" style="padding: 0px 3px;">
	                                        <div class="single_counter_two_right" style="padding: 2px 10px; margin: 2px 5px; background-color: #043D5D;color:white;">
	                                            <h2 class="statistic-counter_two">{{ $total_tickets }}</h2>
	                                            <p>Tickets Generated</p>
	                                        </div>
	                                    </div><!-- End off col-sm-3 -->
	                                    <div class="col-sm-3 col-xs-12" style="padding: 0px 3px;">
	                                        <div class="single_counter_two_right" style="padding: 2px 10px; margin: 2px 5px; background-color: #032E46;color:white;">
	                                            <h2 class="statistic-counter_two">{{ $complaints }}</h2>
	                                            <p>Unresolved Complaints</p>
	                                        </div>
	                                    </div><!-- End off col-sm-3 -->
	                                    <div class="col-sm-3 col-xs-12" style="padding: 0px 3px;">
	                                        <div class="single_counter_two_right" style="padding: 2px 10px; margin: 2px 5px; background-color: #0F595E;color:white;">
	                                            <h2 class="statistic-counter_two">{{ $authored_tickets }}</h2>
	                                            <p>Authored Tickets</p>
	                                        </div>
	                                    </div><!-- End off col-sm-3 -->
	                                    <div class="col-sm-3 col-xs-12" style="padding: 0px 3px;">
	                                        <div class="single_counter_two_right" style="padding: 2px 10px; margin: 2px 5px; background-color: #23B684;color:white;">
	                                            <h2 class="statistic-counter_two">{{ $open_tickets }}</h2>
	                                            <p>Open Tickets</p>
	                                        </div>
	                                    </div>
	                                </div><!-- End off col-sm-3 -->
	                            </div>
	                        </div>
	                    </div><!-- End off row -->
	                </div><!-- End off container -->
	            </div><!-- End off overlay -->
        	</section><!-- End off Counter section -->
			<p class="text-muted">Note: Other actions will be shown when a row has been selected</p>
			<table class="table table-hover table-bordered table-condensed" id="ticketTable">
				<thead>
					<th>ID</th>
					<th>Date</th>
					<th>Tag</th>
					<th>Title</th>
					<th>Details</th>
					<th>Type</th>
					<th>Assigned To</th>
					<th>Author</th>
					<th>Status</th>
					<th></th>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript" src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.counterup.min.js') }}"></script>
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {
		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

		@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
		url = `?type=Complaint&status=Open`
		@else
		url = `?type=Complaint`
		@endif
	  	var table = $('#ticketTable').DataTable({
  		    order: [[ 0, "desc" ]],
	  		select: {
	  			style: 'single'
	  		},
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
	    	"dom": "<'row'<'col-sm-5'l><'col-sm-4 text-center'<'filter'>><'col-sm-3'f>><<'toolbar'>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
				"processing": true,
	      ajax: url,
	      columns: [
	          { data: "id" },
	          {
            	data: function(callback){
            		return moment(callback.date).format("dddd, MMMM Do YYYY, h:mm a");
            	} 
	           },
	          { data: "tag" },
	          { data: "title" },
	          { data: "details" },
	          { data: "tickettype" },
	          { data: "staffassigned" },
	          { data: "author" },
	          { data: "status"},
	          { data: function(callback){
	          	return "<a href='{{ url("ticket/history") }}" + '/' +  callback.id + "' class='btn btn-sm btn-default btn-block'>View More</a>"
	          } }
	    	],
	  	});
 
    table
        .on( 'select', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' selection</b> - '+JSON.stringify( rowData )+'</div>' );
            if(table.row('.selected').data().status == 'Open')
            {
	            $('#assign').show()
	            $('#resolve').show()
            	$('#close').show()	
            }
            if(table.row('.selected').data().status == 'Closed' && table.row('.selected').data().tickettype == 'Complaint')
            {
            	$('#reopen').show()	
            }
        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' <i>de</i>selection</b> - '+JSON.stringify( rowData )+'</div>' );
            $('#assign').hide()
            $('#resolve').hide()
            $('#close').hide()
            $('#reopen').hide()
        } );



	    $('#table tbody').on( 'click', 'tr', function () {
	      if ( $(this).hasClass('selected') ) {
	          $(this).removeClass('selected');
	      }
	      else {
	          table.$('tr.selected').removeClass('selected');
	          $(this).addClass('selected');
	      }
	    } );

	 	$("div.toolbar").html(`
			<button id="add" class="btn btn-primary btn-flat" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon glyphicon-plus"></span>  Create</button>	
			@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
			<button id="maintenance" class="btn btn-warning btn-flat" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon glyphicon-wrench"></span>  Maintenance</button>	
			<button id="assign" class="btn btn-success btn-flat" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon glyphicon-share-alt"></span> Assign </button>
			<button id="resolve" class="btn btn-default btn-flat" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon glyphicon-check"></span> Create an Action</button>
			@endif
			@if(Auth::user()->accesslevel == 0)
			<button id="close" class="btn btn-danger btn-flat" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon glyphicon-off"></span> Close</button>
			<button id="reopen" class="btn btn-info btn-flat" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon glyphicon-off"></span> Reopen</button>
			@endif
		`);

		@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
		$('div.filter').html(`
			<span class='text-muted'>Type:</span><div class="btn-group">
			  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="tickettype-filter" style="padding: 7px 7px"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <span id="tickettype-button"></span> <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" id="tickettype-button">
		   		@foreach($tickettype as $tickettype)
				<li role="presentation">
					<a class="tickettype"  data-name='{{ $tickettype->type }}'>{{ $tickettype->type }}</a>
				</li>
			    @endforeach
			  </ul>
			</div>
			<span class='text-muted'>Status:</span><div class="btn-group">
			  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="ticketstatus-filter" style="padding: 7px 7px"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <span id="ticketstatus-button"></span> <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" id="ticketstatus-button">
			      @foreach($ticketstatus as $ticketstatus)
					<li role="presentation">
						<a class="ticketstatus"  data-name='{{ $ticketstatus }}'>{{ $ticketstatus }}</a>
					</li>
			    @endforeach
			  </ul>
			</div>
		`);

		$('#tickettype-button').text( $('.tickettype:first').text() )
		$('#ticketstatus-button').text( 'Open' )

		$('.tickettype').on('click',function(event)
		{
			$('#tickettype-button').text($(this).data('name'))
			url = "{{ url('ticket') }}" + '?status=' + $('#ticketstatus-button').text() + '&type=' + $('#tickettype-button').text()
			table.ajax.url(url).load();
		})

		$('.ticketstatus').on('click',function(event)
		{
			$('#ticketstatus-button').text($(this).data('name'))
			url = "{{ url('ticket') }}" + '?status=' + $('#ticketstatus-button').text() + '&type=' + $('#tickettype-button').text()
			table.ajax.url(url).load();
		})
		@endif

		$('#add').on('click',function(){
			window.location.href = '{{ url('ticket/create') }}';
		});

		@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
		$('#maintenance').on('click',function(){
			window.location.href = '{{ url('ticket/maintenance') }}';
		});

	    $('#assign').click( function () {
			try
			{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					$('#transfer-id').val(table.row('.selected').data().id)
					$('#transfer-date').text(moment(table.row('.selected').data().date).format("dddd, MMMM Do YYYY, h:mm a"))
					$('#transfer-tag').text(table.row('.selected').data().tag)
					$('#transfer-title').text(table.row('.selected').data().title)
					$('#transfer-details').text(table.row('.selected').data().details)
					$('#transfer-author').text(table.row('.selected').data().author)
					$('#transfer-staffid').val(table.row('.selected').data().staff_id)
					$('#transfer-assigned').text(table.row('.selected').data().staffassigned)
					$('#transferTicketModal').modal('show')
				}
			}
			catch( error )
			{
				swal('Oops..','You must choose atleast 1 row','error');
			}
	    } );

	    $('#resolve').click( function () {
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					if(table.row('.selected').data().tickettype == 'Complaint')
					{
						$('#resolve-id').val(table.row('.selected').data().id);
	    				tag = table.row('.selected').data().tag
						if(tag.indexOf('PC') !== -1 || tag.indexOf('Item') !== -1)
						{
							if(tag.indexOf('PC') !== -1)
							{
								$('#item-tag').val(tag.substr(4))
							}

							if(tag.indexOf('Item') !== -1)
							{
								$('#item-tag').val(tag.substr(6))
							}

							$('#resolve-equipment').show()
						}
						else
						{
							$('#item-tag').val("")
							$('#resolve-equipment').hide()
						}

						$('#resolveTicketModal').modal('show')
					} 
					else 
					{
						swal('Error!','Only complaints can be resolved','error')
					}
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
	    } );
	    @endif

		@if(Auth::user()->accesslevel == 0)
	    $('#close').click( function () {
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					// do other things for a valid form
			        swal({
			          title: "Are you sure?",
			          text: "Do you really want to close the ticket?",
			          type: "warning",
			          showCancelButton: true,
			          confirmButtonText: "Yes, close it!",
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
								url: '{{ url("ticket") }}' + "/" + table.row('.selected').data().id,
								data: {
									'id': table.row('.selected').data().id
								},
								dataType: 'json',
								success: function(response){
									if(response.length > 0){
										swal('Operation Successful','Ticket has been closed','success')
						        		table.ajax.reload().order([ 0, "desc" ]);
									}else{
										swal('Operation Unsuccessful','Error occurred while closing a ticket','error')
									}
								},
								error: function(){
									swal('Operation Unsuccessful','Error occurred while closing a ticket','error')
								}
							});
				          } else {
				            swal("Cancelled", "Operation Cancelled", "error");
			          	}
			        })
				}
			}
			catch( error )
			{
				swal('Oops..','You must choose atleast 1 row','error');
			}
	    } );

	    $('#reopen').click( function () {
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
			        // do other things for a valid form
			        swal({
			          title: "Are you sure?",
			          text: "Do you really want to reopen the ticket.",
			          type: "warning",
			          showCancelButton: true,
			          confirmButtonText: "Yes, reopen it!",
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
							url: '{{ url("ticket") }}' + "/" + table.row('.selected').data().id + '/reopen',
							data: {
								'id': table.row('.selected').data().id
							},
							dataType: 'json',
							success: function(response){
								if(response.length > 0){
									swal('Operation Successful','Ticket has been reopened','success')
					        		table.ajax.reload().order([ 0, "desc" ]);
								}else{
									swal('Operation Unsuccessful','Error occurred while reopening a ticket','error')
								}
							},
							error: function(){
								swal('Operation Unsuccessful','Error occurred while reopening a ticket','error')
							}
						});
			          } else {
			            swal("Cancelled", "Operation Cancelled", "error");
			          }
			        })
				}
			}
			catch( error )
			{
				swal('Oops..','You must choose atleast 1 row','error');
			}
	    } );
	    @endif

	    // Counter 
        jQuery('.statistic-counter_two').counterUp({
            delay: 10,
            time: 2000
        });

		$('#page-body').show();
    });
</script>
@stop
