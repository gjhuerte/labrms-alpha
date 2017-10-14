@extends('layouts.master-blue')
@section('title')
Workstation Profile
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/jquery-ui.css')) }}
{{ HTML::style(asset('css/font-awesome.min.css')) }}
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

	.line-either-side {
		overflow: hidden;
		text-align: center;
	}
	.line-either-side:before,
	.line-either-side:after {
		background-color: #e5e5e5;
		content: "";
		display: inline-block;
		height: 1px;
		position: relative;
		vertical-align: middle;
		width: 50%;
	}
	.line-either-side:before {
		right: 0.5em;
		margin-left: -50%;
	}
	.line-either-side:after {
		left: 0.5em;
		margin-right: -50%;
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
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
@include('modal.workstation.software.install')
	@include('modal.workstation.software.edit')
	<div class="panel panel-default" style="padding:0px 20px">
		<div class="panel-body">
			<div class="col-sm-12">
				<legend><h3 class="text-muted">Workstation {{ $workstation->name }}</h3></legend>
			</div>
			<div class="col-sm-12">	
				<ul class="breadcrumb">
					<li><a href="{{ url('workstation') }}">Workstation</a></li>
					<li class="active">{{ $workstation->id }}</li>
				</ul>
			</div>
			<div class="col-sm-12">
				  <!-- Default panel contents -->
				  <h3 class="line-either-side text-info">Basic Information</h3>
			</div>
			<div class="col-sm-9">
				<div class="panel panel-default" style="border:none;">
				  <!-- List group -->
				  <ul class="list-unstyled">
				    <li class="text-muted" style="padding:10px;letter-spacing:3px;">
				    	<span class="col-sm-6"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Name: </span>
				    	<span>{{ $workstation->name }}</span></li>
				    <li class="text-muted " style="padding:10px;letter-spacing:3px;">
				    	<span class="col-sm-6" style="font-size:12px;"><i class="fa fa-key" aria-hidden="true"></i> Operating System License Key: </span>
				    	<span>{{ $workstation->oskey }}</span></li>
				    <li class="text-muted " style="padding:10px;letter-spacing:3px;">
				    	<span class="col-sm-6"><i class="fa fa-server" aria-hidden="true"></i> System Unit: </span>
				    	<span>{{ ($workstation->systemunit) ? $workstation->systemunit->propertynumber : "" }}</span>
				    </li>
				    <li class="text-muted " style="padding:10px;letter-spacing:3px;">
				    	<span class="col-sm-6"><i class="fa fa-desktop" aria-hidden="true"></i> Monitor: </span>
				    	<span>{{ ($workstation->monitor) ? $workstation->monitor->propertynumber : "" }}</span>
				    </li>
				    <li class="text-muted " style="padding:10px;letter-spacing:3px;">
				    	<span class="col-sm-6"><i class="fa fa-power-off" aria-hidden="true"></i> AVR: </span>
				    	<span>{{ ($workstation->avr) ? $workstation->avr->propertynumber : "" }}</span>
				    </li>
				    <li class="text-muted " style="padding:10px;letter-spacing:3px;">

				    	<span class="col-sm-6"><i class="fa fa-keyboard-o" aria-hidden="true"></i> Keyboard: </span>
				    	<span>{{ ($workstation->keyboard) ? $workstation->keyboard->propertynumber : "" }}</span>
				    </li>
				    <li class="text-muted " style="padding:10px;letter-spacing:3px;">
				    	<span class="col-sm-6"><i class="fa fa-mouse-pointer" aria-hidden="true"></i> Mouse: </span>
				    	<span>{{ $workstation->mouse }}</span>
				    </li>
				    <li class="text-muted " style="padding:10px;letter-spacing:3px;">
				    	<span class="col-sm-6"><i class="fa fa-location-arrow" aria-hidden="true"></i> Location: </span>
				    	<span>{{ $workstation->systemunit->location }}</span>
				    </li>
				  </ul>
				</div>
			</div>
			<div class="col-sm-2">
				 <!--Counter Section-->
		        <section id="counter_two" class="counter_two">
		            <div class="overlay" style="border: none;">
		                        <div class="main_counter_two sections text-center text-muted">
	                                <div class="row pull-right">
	                                    <div class="col-sm-12 col-xs-12" style="margin:5px;background-color: #043D5D;color:white;">
	                                    	{{-- <i class="fa fa-bullhorn fa-2x" aria-hidden="true"></i> --}}
	                                        <div class="single_counter_two_right">
	                                            <h2 class="statistic-counter_two">{{ isset($total_tickets) ? $total_tickets : 0 }}</h2>
	                                            <p>Complaints</p>
	                                        </div>
	                                    </div><!-- End off col-sm-3 -->
	                                    <div class="col-sm-12 col-xs-12" style="margin:5px;background-color: #032E46;color:white;">
	                                    	{{-- <i class="fa fa-cog fa-2x" aria-hidden="true"></i> --}}
	                                        <div class="single_counter_two_right">
	                                            <h2 class="statistic-counter_two">{{ isset($mouseissued) ? $mouseissued : 0 }}</h2>
	                                            <p>Mouse Issued</p>
	                                        </div>
	                                    </div><!-- End off col-sm-3 -->
	                                </div><!-- End off col-sm-3 -->
		                        </div>
		            </div><!-- End off overlay -->
	        	</section><!-- End off Counter section -->
			</div>
			<div class="col-sm-12">
			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">History</a></li>
			    <li role="presentation" class="active"><a href="#software" aria-controls="software" role="tab" data-toggle="tab">Software</a></li>
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content">
			    <div role="tabpanel" class="tab-pane" id="history">
			    	<div class="panel panel-body" style="padding: 10px;">
						<table class="table table-bordered" id="historyTable" style="width:100%;">
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
			    <div role="tabpanel" class="tab-pane active" id="software">
			    	<div class="panel panel-body" style="padding: 10px;">
						<table class="table table-bordered" id="softwareTable">
							<thead>
								<th>Software</th>
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
@stop
@section('script')
{{ HTML::script(asset('js/jquery-ui.js')) }}
<script type="text/javascript" src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.counterup.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){

	var historyTable = $('#historyTable').DataTable( {
	    language: {
	        searchPlaceholder: "Search..."
	    },
	    order: [[ 0, "desc" ]],
		"processing": true,
        ajax: "{{ url("ticket/workstation/$workstation->id") }}",
        columns: [
        	{ data: 'id' },
        	{ data: 'ticketname' },
        	{ data: 'details' },
        	{ data: 'author' },
        	{ data: 'status' }
        ],
    } );

	var table = $('#softwareTable').DataTable( {
		"pageLength": 100,
  		select: {
  			style: 'multiple'
  		},
    	columnDefs:[
			{ targets: 'no-sort', orderable: false },
    	],
	    language: {
	        searchPlaceholder: "Search..."
	    },
		"processing": true,
        ajax: "{{ url("workstation/$workstation->id") }}",
        columns: [
        	{ data: function(callback){
        		return callback.softwarename
        	}},
        	{ data: function(callback){

        		edit = `<button class="btn btn-default btn-sm pull-right" data-pc='{{ $workstation->id }}' data-software='`+ callback.id +`' data-target='#updateSoftwareWorkstationModal' data-toggle='modal'>Change License</button>`
        		button = `<button class="remove btn btn-danger btn-sm pull-right" data-pc='{{ $workstation->id }}' data-software="`+ callback.id +`">Uninstall</button>`

        		try
        		{
        			return `Installed:  ` + " " + callback.pcsoftware.softwarelicense.key + edit + button
        		} catch (e) {
        			try {
        				if(!callback.pcsoftware.isEmpty)
        				return `Installed` + edit + button
        			} catch (e) {
        				return "<i>Not Installed</i>  <button class='install btn btn-success btn-sm pull-right' data-pc='{{ $workstation->id }}' data-software='"+ callback.id +"' data-target='#installSoftwareWorkstationModal' data-toggle='modal'>Install</button>"
        			}
        		}
        	}}
        ],
    } );

    $('#softwareTable').on('click','.remove',function(){
    	pc = $(this).data('pc')
    	software = $(this).data('software')

    	$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
    		type: 'delete',
    		url: '{{ url("workstation/software/$workstation->id/remove") }}',
    		data: {
    			'software': software
    		},
    		dataType: 'json',
    		success: function(response){
    			if(response == 'success')
    				swal('Operation Success','','success')
    			else
    				swal('Error occurred while processing your request','','error')

    			table.ajax.reload()
    			historyTable.ajax.reload()
    		}
    	})

    })

    $('#installSoftwareWorkstationModal').on('hide.bs.modal',function(){
    	table.ajax.reload()
		historyTable.ajax.reload()
    })

    $('#updateSoftwareWorkstationModal').on('hide.bs.modal',function(){
    	table.ajax.reload()
		historyTable.ajax.reload()
    })

    // Counter 
    jQuery('.statistic-counter_two').counterUp({
        delay: 10,
        time: 200
    });

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
