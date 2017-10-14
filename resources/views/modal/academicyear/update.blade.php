<div class="modal fade" id="academicYearUpdateModal" tabindex="-1" role="dialog" aria-labelledby="createInventoryModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<legend><h3 class="text-muted">Academic Year Update</h3></legend>
				{{ Form::open(['method'=>'put','route'=>array('academicyear.update',csrf_token()),'class'=>'form-horizontal','id'=>'academicYearForm']) }}
				<div class="clearfix"></div>
				<input type=hidden name=id id=modal-id value="" />
				<div class="form-group">
		 			<div class="col-sm-12">
					{{ Form::label('Start') }}
			 		{{ Form::text('start',Input::old('modal-start'),[
			 			'class' => 'form-control',
			 			'id' => 'modal-start',
			 			'style' => 'background-color:white;',
			 			'readonly',
			 			'placeholder' => 'Academic Year Start'
			 		]) }}
			 		</div>
		 		</div>
		 		<div class="form-group">
		 			<div class="col-sm-12">
					{{ Form::label('End') }}
			 		{{ Form::text('end',Input::old('modal-end'),[
			 			'class' => 'form-control',
			 			'id' => 'modal-end',
			 			'style' => 'background-color:white;',
			 			'readonly',
			 			'placeholder' => 'Academic Year End'
			 		]) }}
			 		</div>
			 	</div>
		 		<div class="form-group">
		 			<div class="col-sm-12">
		 			<button type="submit" class="btn btn-lg btn-primary btn-block">Update</button>
			 		</div>
			 	</div>
	 			{{ Form::close() }}
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>
<script>
	$(document).ready(function(){
	    $("#modal-start").datepicker({
	      language: 'en',
	      showOtherYears: false,
	      todayButton: true,
	      autoClose: true,
	      onSelect: function(){
	        $('#modal-start').val(moment($('#modal-start').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
	      }
	    });

	    $("#modal-end").datepicker({
	      language: 'en',
	      showOtherYears: false,
	      todayButton: true,
	      autoClose: true,
	      onSelect: function(){
	        $('#modal-end').val(moment($('#modal-end').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
	      }
	    });
    })
</script>
