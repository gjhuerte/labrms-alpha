@extends('layouts.master-blue')
@section('title')
Room Category
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
	<div class="col-sm-offset-2 col-sm-8" id="room-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Laboratory Room Categories</h3></legend>
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
			<table class="table table-hover table-condensed table-bordered table-striped" id="roomTable">
				<thead>
					<th>Category</th>
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
		var table = $('#roomTable').DataTable( {
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
	        ajax: "{{ url('room/category') }}",
	        columns: [
	            { data: "category" },
	            { data: function(callback){
	            	return `
	            		<button type="button" data-category="`+callback.category+`" class="btn btn-sm btn-default edit">Edit</button>
	            		<button type="button" data-category="`+callback.category+`" class="btn btn-sm btn-danger delete">Delete</button>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
	 		{{ Form::open(['method'=>'post','route'=>'room.category.store','id'=>'categoryCreationForm']) }}
	 		{{ Form::text('name',Input::old('name'),[
	 			'class' => 'form-control',
	 			'id' => 'category-name',
	 			'style' => 'display:none',
	 			'placeholder' => 'Category Name'
	 		]) }}
 			<button type="button" id="new" class="btn btn-success" style="margin-right:5px;" ><span class="glyphicon glyphicon-plus"></span> Add</button>
 			<button type="button" id="hide" class="btn btn-default" style="margin-right:5px;display:none;"><span class="glyphicon glyphicon-eye-close"></span> Hide</button>
 			{{ Form::close() }}
		`);

		$('#new').on('click',function(){
			if($('#category-name').is(':hidden'))
			{		
				$('#category-name').toggle(400)
				$('#hide').toggle(400)
			}
			else
			{
				$('#categoryCreationForm').submit()
			}
		})

		$('#hide').on('click',function(){
			$('#category-name').toggle(400)
			$('#hide').toggle(400)
		})

		$('#roomTable').on('click','.edit',function(){
	    	category = $(this).data('category')
	    	swal({
			  title: "Input Category!",
			  text: "Input the new category you want to update it to",
			  type: "input",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  animation: "slide-from-top",
			  inputValue: category
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
			  	url: '{{ url("room/category") }}' + '/' + category,
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

	    $('#roomTable').on('click','.delete',function(){
	    	category = $(this).data('category')
	        swal({
	          title: "Are you sure?",
	          text: "This room will be removed from database?",
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
					url: '{{ url("room/category") }}' + "/" + category,
					data: {
						'id': category
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Room Category removed','success')
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
