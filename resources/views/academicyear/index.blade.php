@extends('layouts.master-blue')
@section('title')
Academic Year
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/datepicker.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body{
		display: none;
	}
	.datepicker{z-index:1151 !important;}
</style>
@stop
@section('content')
@include('modal.academicyear.update')
<div class="container-fluid" id="page-body">
	<div class="col-sm-offset-2 col-sm-8" id="software-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Academic Year</h3></legend>
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
			<table class="table table-hover table-condensed table-bordered table-striped" id="academicYearTable" style='width:100%;'>
				<thead>
					<th>ID</th>
					<th>Name</th>
					<th>Start</th>
					<th>End</th>
					<th class="col-sm-2 no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/datepicker.min.js')) }}
{{ HTML::script(asset('js/datepicker.en.js')) }}
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#academicYearTable').DataTable( {
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
	        ajax: "{{ url('academicyear') }}",
	        columns: [
	            { data: "id" },
	            { data: "name" },
	            { data: function(callback){
	            	if(callback.start)
	            		return moment(callback.start).format('MMMM DD, YYYY')
	            	else
	            		return ''
	            } },
	            { data: function(callback){
	            	if(callback.end)
	            		return moment(callback.end).format('MMMM DD, YYYY')
	            	else
	            		return ''
	            } },
	            { data: function(callback){
	            	return `
	            		<button type="button" data-start="`+callback.start+`" data-end="`+callback.end+`" data-id="`+callback.id+`" class="btn btn-sm btn-default edit">Edit</button>
	            		<button type="button" data-id="`+callback.id+`" class="btn btn-sm btn-danger delete">Delete</button>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
	 		{{ Form::open(['method'=>'post','route'=>'academicyear.store','id'=>'academicYearCreationForm']) }}
	 		{{ Form::text('start',Input::old('start'),[
	 			'class' => 'form-control',
	 			'id' => 'start',
	 			'style' => 'display:none;background-color:white;',
	 			'readonly',
	 			'placeholder' => 'Academic Year Start'
	 		]) }}
	 		{{ Form::text('end',Input::old('end'),[
	 			'class' => 'form-control',
	 			'id' => 'end',
	 			'style' => 'display:none;background-color:white;',
	 			'readonly',
	 			'placeholder' => 'Academic Year End'
	 		]) }}
 			<button type="button" id="new" class="btn btn-success" style="margin-right:5px;" ><span class="glyphicon glyphicon-plus"></span> Add</button>
 			<button type="button" id="hide" class="btn btn-default" style="margin-right:5px;display:none;"><span class="glyphicon glyphicon-eye-close"></span> Hide</button>
 			{{ Form::close() }}
		`);

		$('#new').on('click',function(){
			if($('#start').is(':hidden'))
			{		
				$('#start').toggle(400)
				$('#end').toggle(400)
				$('#hide').toggle(400)
			}
			else
			{
				$('#academicYearCreationForm').submit()
			}
		})

		$('#hide').on('click',function(){
			$('#start').toggle(400)
			$('#end').toggle(400)
			$('#hide').toggle(400)
		})

		$('#academicYearTable').on('click','.edit',function(){
	    	$("#modal-start").val(moment($(this).data('start')).format('MMMM DD, YYYY'));
	    	$("#modal-end").val(moment($(this).data('end')).format('MMMM DD, YYYY'));
	    	$("#modal-id").val($(this).data('id'));
	    	$('#academicYearUpdateModal').modal('show');
		});

	    $('#academicYearTable').on('click','.delete',function(){
	    	id = $(this).data('id')
	        swal({
	          title: "Are you sure?",
	          text: "This Academic Year will be removed. Do you want to continue?",
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
					url: '{{ url("academicyear") }}' + "/" + type,
					data: {
						'id': id
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Academic Year removed','success')
			        	}else{
							swal('Operation Unsuccessful','Error occurred while deleting a record','error')
						}

		        		table.ajax.reload();
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

	    $("#start").datepicker({
	      language: 'en',
	      showOtherYears: false,
	      todayButton: true,
	      autoClose: true,
	      onSelect: function(){
	        $('#start').val(moment($('#start').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
	      }
	    });

	    $("#end").datepicker({
	      language: 'en',
	      showOtherYears: false,
	      todayButton: true,
	      autoClose: true,
	      onSelect: function(){
	        $('#end').val(moment($('#end').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
	      }
	    });

	    $("#start").val(moment('{{ Carbon\Carbon::now() }}').format('MMMM DD, YYYY'));
	    $("#end").val(moment('{{ Carbon\Carbon::now()->addMonths(10) }}').format('MMMM DD, YYYY'));

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
