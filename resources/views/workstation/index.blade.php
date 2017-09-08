@extends('layouts.master-blue')
@section('title')
Workstation
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{-- css for select --}}
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
<link rel="stylesheet" href="{{ url('css/style.css') }}"  />
<style>
	#page-body,#deploy,#transfer,#delete,#update{
		display: none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
@include('modal.workstation.deploy')
@include('modal.workstation.transfer')
@include('modal.workstation.condemn')
	<div class="col-md-12" id="workstation-info">
		<div class="panel panel-body  table-responsive">
			<legend><h3 class="text-muted">Workstation</h3></legend>
			<p class="text-muted">Note: Other actions will be shown when a row has been selected</p>
				<table class="table table-hover table-striped table-bordered" id="workstationTable">
					<thead>
						<th>ID</th>
						<th>OS</th>
						<th>Name</th>
						<th>System Unit</th>
						<th>Monitor</th>
						<th>AVR</th>
						<th>Keyboard</th>
						<th>Mouse</th>
						<th>Location</th>
						<th class="no-sort"></th>
					</thead>
				</table>
		</div>
		<input type="hidden" val="" name="selected" id="selected" />
	</div>
</div>
@stop
@section('script')
{{-- javascript for select --}}
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">

	$(document).ready(function() {

		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

    	var table = $('#workstationTable').DataTable( {
	  		select: {
	  			style: 'single'
	  		},
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('workstation') }}",
	        columns: [
	        	{ data: 'id' },
	            { data: function(callback){
	            	var ret_val;
	            	try{
	            		ret_val = callback.oskey;
	            		if (ret_val == "" || ret_val == null) ret_val = 'None';
	            	} catch ( error){ 
	            		ret_val = 'None';
	            	}
	            	return ret_val;
	            }},
	            { data: function(callback)
	            {
	            	var ret_val;
	            	try{
	            		ret_val = callback.name;
	            	} catch (error) {
	            		ret_val = 'None';
	            	} 
            		return ret_val;
	            } },
	            { data: function(callback)
	            {
	            	var ret_val;
	            	try{
	            		ret_val = callback.systemunit.propertynumber;
	            	} catch (error) {
	            		ret_val = 'None';
	            	} 
            		return ret_val;
	            } },
	            { data: function(callback)
	            {
	            	var ret_val;
	            	try{
	            		ret_val = callback.monitor.propertynumber;
	            	} catch (error) {
	            		ret_val = 'None';
	            	} 
            		return ret_val;
	            } },
	            { data: function(callback)
	            {
	            	var ret_val;
	            	try{
	            		ret_val = callback.avr.propertynumber;
	            	} catch (error) {
	            		ret_val = 'None';
	            	} 
            		return ret_val;
	            } },
	            { data: function(callback)
	            {
	            	var ret_val;
	            	try{
	            		ret_val = callback.keyboard.propertynumber;
	            	} catch (error) {
	            		ret_val = 'None';
	            	} 
            		return ret_val;
	            } },
	            { data: function(callback){
	            	if(callback.mouse) {
	            		return callback.mouse;
	            	} else{
	            		return 'None';
	            	}
	            } },
	            { data: function(callback)
	            {
	            	var ret_val;
	            	try{
	            		ret_val = callback.systemunit.roominventory.room.name;
	            	} catch (error) {
	            		ret_val = 'None';
	            	} 
            		return ret_val;
	            } },
	            { data: function(callback){
	            	return `<a href="{{ url('workstation') }}/`+callback.id+`" class="btn btn-default btn-sm btn-block"><span class="glyphicon glyphicon-eye-open"></span> 	View</a>`
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`

	 			<a id="new" class="btn btn-primary" style="margin-right:5px;padding: 5px 10px;" href="{{ url('workstation/create') }}">
	 				<span class="glyphicon glyphicon-plus"></span>  Add
	 			</a>
	 			<button id="update" class="btn btn-success" style="margin-right:5px;padding: 5px 10px;">
	 				<span class="glyphicon glyphicon-wrench"></span>  Update Parts
	 			</button>
	 			<button id="deploy" class="btn btn-default" style="margin-right:5px;padding: 5px 10px;">
	 				<span class="glyphicon glyphicon-share-alt"></span>  Deploy
	 			</button>
	 			<button id="transfer" class="btn btn-warning" style="margin-right:5px;padding: 5px 10px;">
	 				<span class="glyphicon glyphicon-share"></span>  Transfer
	 			</button>
	 			<button id="delete" class="btn btn-danger" data-loading-text="Loading..." style="margin-right:5px;padding: 5px 10px;">
	 				<span class="glyphicon glyphicon-trash"></span> Condemn
	 			</button>
		`);
 
    table
        .on( 'select', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' selection</b> - '+JSON.stringify( rowData )+'</div>' );
            if(table.row('.selected').data().systemunit.roominventory.room.name == 'Server')
            {
            	$('#deploy').show()
            }
            else
            {
            	$('#transfer').show()
            }

            $('#update').show()
            $('#delete').show()
        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' <i>de</i>selection</b> - '+JSON.stringify( rowData )+'</div>' );
            $('#deploy').hide()
            $('#transfer').hide()
            $('#update').hide()
            $('#delete').hide()
        } );

		$('#deploy').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					var array = [];
					table.rows('.selected').every(function(row) {
						array.push(table.row(row).data().id);
					})   

					$('#deploy-systemunit').text( table.row('.selected').data().systemunit.propertynumber )
					$('#deploy-monitor').text( table.row('.selected').data().monitor.propertynumber )
					$('#deploy-avr').text( table.row('.selected').data().avr.propertynumber )
					$('#deploy-keyboard').text( table.row('.selected').data().keyboard.propertynumber )
					$('#deploy-mouse').text( table.row('.selected').data().mouse )
					$('#deploy-name-info').text( table.row('.selected').data().name )

					$('#selected').val(array);
					$('#deployWorkstationModal').modal('show');
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		})

		$('#update').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = "{{ url('workstation') }}" + '/' + table.row('.selected').data().id + '/edit'
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		})

		$('#delete').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					var array = [];
					table.rows('.selected').every(function(row) {
						array.push(table.row(row).data().id)
					})   

					$('#selected').val(array);
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		})

		$('#transfer').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					var array = [];
					table.rows('.selected').every(function(row) {
						array.push(table.row(row).data().id);
					})   

					$('#transfer-systemunit').text( table.row('.selected').data().systemunit.propertynumber )
					$('#transfer-monitor').text( table.row('.selected').data().monitor.propertynumber )
					$('#transfer-avr').text( table.row('.selected').data().avr.propertynumber )
					$('#transfer-keyboard').text( table.row('.selected').data().keyboard.propertynumber )
					$('#transfer-mouse').text( table.row('.selected').data().mouse )
					$('#transfer-name-info').text( table.row('.selected').data().name )

					$('#selected').val(array);
					$('#transferWorkstationModal').modal('show');
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		})

		$('#modal-deploy').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
		   			var $btn = $(this).button('loading')
					$.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
						type: 'post',
						url: '{{ route("workstation.deploy") }}',
						data: {
							'room' : $('#room').val(),
							'items': $('#items').val(),
							'name': $('#deploy-name').val()
						},
						dataType: 'json',
						success: function(response){
							if(response == 'success'){
								$('#deployWorkstationModal').modal('hide');
								$('#deploy').hide()
								swal('Success','Workstation/s successfully deployed','success');
							} else if(response == 'error'){
								swal('Oops','Something went wrong while deploying workstation/s','error');
							}

							table.ajax.reload();
		    				$btn.button('reset')
						},
						error: function(response){
		   				 	$btn.button('reset')
							swal('Error Occurred','Something went wrong while sending your request. Please reload the page','error')
						}
					});
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		});

		$('#modal-transfer').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
		   			var $btn = $(this).button('loading')
					$.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
						type: 'post',
						url: '{{ route("workstation.transfer") }}',
						data: {
							'room' : $('#transfer-room').val(),
							'items': $('#transfer-items').val(),
							'name': $('#transfer-name').val()
						},
						dataType: 'json',
						success: function(response){
							if(response == 'success'){
           						$('#transfer').hide()
								$('#transferWorkstationModal').modal('hide');
								swal('Success','Workstation/s transferred','success');
							} else if(response == 'error'){
								swal('Oops','Something went wrong while changing the location of a workstation','error');
							}

							table.ajax.reload();
		    				$btn.button('reset')
						},
						error: function(response){
		   				 	$btn.button('reset')
							swal('Error Occurred','Something went wrong while sending your request. Please reload the page','error')
						}
					});
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		});


		$('#edit').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = "{{ url('workstation') }}" + '/' + table.row('.selected').data().id + '/edit';
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		});

	    $('#delete').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					$('#condemn-systemunit-text').text( function(){
						try
						{
							$(this).parents('li').show()
							return table.row('.selected').data().systemunit.propertynumber;
						}
						catch(e)
						{
							$(this).parents('li').hide()
							return '';
						}
					} )
					$('#condemn-monitor-text').text( function(){
						try
						{
							$(this).parents('li').show()
							return  table.row('.selected').data().monitor.propertynumber;
						}
						catch(e)
						{
							$(this).parents('li').hide()
							return '';
						}
					}  )
					$('#condemn-avr-text').text( function(){
						try
						{
							$(this).parents('li').show()
							return table.row('.selected').data().avr.propertynumber;
						}
						catch(e)
						{
							$(this).parents('li').hide()
							return '';
						}
					} )
					$('#condemn-keyboard-text').text( function(){
						try
						{
							$(this).parents('li').show()
							return table.row('.selected').data().keyboard.propertynumber;
						}
						catch(e)
						{
							$(this).parents('li').hide()
							return '';
						}
					}  )
					$('#condemnWorkstationModal').modal('show');
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
	    });

	    $('#condemn-button').click(function(){
	    	avr = null
	    	keyboard = null
	    	monitor = null
	    	systemunit = null

	    	if($('#condemn-keyboard-text').is(':visible'))
	    	{
	    		if($('#condemn-keyboard').is(':checked'))
	    		{
	    			keyboard = true
	    		}
	    	}

	    	if($('#condemn-avr-text').is(':visible'))
	    	{
	    		if($('#condemn-avr').is(':checked'))
	    		{
	    			avr = true
	    		}
	    	}

	    	if($('#condemn-monitor-text').is(':visible'))
	    	{
	    		if($('#condemn-monitor').is(':checked'))
	    		{
	    			monitor = true
	    		}
	    	}

	    	if($('#condemn-systemunit-text').is(':visible'))
	    	{
	    		if($('#condemn-systemunit').is(':checked'))
	    		{
	    			systemunit = true
	    		}
	    	}

			var $btn = $(this).button('loading')
			$.ajax({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
			type: 'delete',
			url: '{{ url("workstation/") }}' + "/" + table.row('.selected').data().id,
			data: {
				'selected': $('#selected').val(),
				'avr': avr,
				'keyboard': keyboard,
				'monitor':monitor,
				'systemunit':systemunit
			},
			dataType: 'json',
			success: function(response){
				if(response == 'success'){
					$('#condemnWorkstationModal').modal('hide');
					swal('Operation Successful','Workstation condemned','success')
	        		table.row('.selected').remove().draw( false );
	        	}else{
					swal('Operation Unsuccessful','Error occured while processing your request','error')
				}

				table.ajax.reload();
				$btn.button('reset')
			},
			error: function(){
				swal('Operation Unsuccessful','Error occured while processing your request','error')
				$btn.button('reset')
			}
		});
	    })

		$('#page-body').show();
  	});
</script>
@stop
