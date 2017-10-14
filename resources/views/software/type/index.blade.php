@extends('layouts.master-blue')
@section('title')
Software Types
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body{
		display: none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-sm-offset-2 col-sm-8" id="software-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Software Types</h3></legend>
	        @if (count($errors) > 0)
	            <div class="alert alert-danger alert-dismissible" role="alert">
	            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <ul style='margin-left: 10px;'>
	                    @foreach ($errors->all() as $error)
	                        <li>{{ $error }}</li>
	                    @endforeach
	                </ul>
	            </div>
	        @endif
			<table class="table table-hover table-condensed table-bordered table-striped" id="softwareTable" style='width:100%;'>
				<thead>
					<th>Types</th>
					<th class="col-sm-2 no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#softwareTable').DataTable( {
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-8'l><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'<'toolbar'>>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('software/type') }}",
	        columns: [
	            { data: "type" },
	            { data: function(callback){
	            	return `
	            		<button type="button" data-type="`+callback.type+`" class="btn btn-sm btn-default edit">Edit</button>
	            		<button type="button" data-type="`+callback.type+`" class="btn btn-sm btn-danger delete">Delete</button>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
	 		{{ Form::open(['method'=>'post','route'=>'software.type.store','id'=>'typeCreationForm']) }}
	 		{{ Form::text('name',Input::old('name'),[
	 			'class' => 'form-control',
	 			'id' => 'type-name',
	 			'style' => 'display:none',
	 			'placeholder' => 'Software Type'
	 		]) }}
 			<button type="button" id="new" class="btn btn-success" style="margin-right:5px;" ><span class="glyphicon glyphicon-plus"></span> Add</button>
 			<button type="button" id="hide" class="btn btn-default" style="margin-right:5px;display:none;"><span class="glyphicon glyphicon-eye-close"></span> Hide</button>
 			{{ Form::close() }}
		`);

		$('#new').on('click',function(){
			if($('#type-name').is(':hidden'))
			{		
				$('#type-name').toggle(400)
				$('#hide').toggle(400)
			}
			else
			{
				$('#typeCreationForm').submit()
			}
		})

		$('#hide').on('click',function(){
			$('#type-name').toggle(400)
			$('#hide').toggle(400)
		})

		$('#softwareTable').on('click','.edit',function(){
	    	type = $(this).data('type')
	    	swal({
			  title: "Input Type!",
			  text: "Input the new type you want to update it to",
			  type: "input",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  animation: "slide-from-top",
			  inputValue: type
			},
			function(inputValue){
			  if (inputValue === false) return false;
			  
			  if (inputValue === "") {
			    swal.showInputError("You need to write something!");
			    return false
			  }
			  
			  $.ajax({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
			  	type: 'put',
			  	url: '{{ url("software/type") }}' + '/' + type,
			  	dataType: 'json',
			  	data: {
			  		'name': inputValue
			  	},
			  	success: function(response){
			  		if(response == 'success')
			  		{
			  			swal('Success','Information Updated','success')	
			  		}
			  		else
			  		swal('Error','Problem Occurred while processing your data','error')
			  		table.ajax.reload();
			  	},
			  	error: function(){
			  		swal('Error','Problem Occurred while processing your data','error')
			  	}
			  })
			});
		});

	    $('#softwareTable').on('click','.delete',function(){
	    	type = $(this).data('type')
	        swal({
	          title: "Are you sure?",
	          text: "This software type will be removed from database?",
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
					url: '{{ url("software/type") }}' + "/" + type,
					data: {
						'id': type
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Software Type removed','success')
			        		table.ajax.reload();
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
	    });

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
