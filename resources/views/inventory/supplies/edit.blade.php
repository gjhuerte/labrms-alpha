@extends('layouts.master-blue')
@section('title')
Inventory | Supplies
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/style.css')) }}
{{ HTML::style(asset('css/jquery-ui.min.css')) }}
<style>
	#page-body{
		display:none;
	}

</style>
@stop
@section('script-include')
{{ HTML::script(asset('js/jquery-ui.js')) }}
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class='col-md-offset-3 col-md-6'>
		<div class="panel panel-body" style="padding-top: 20px;padding-left: 40px;padding-right: 40px;">
	      @if (count($errors) > 0)
         	 <div class="alert alert-danger alert-dismissible" role="alert">
	          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	              <ul class="list-unstyled" style='margin-left: 10px;'>
	                  @foreach ($errors->all() as $error)
	                      <li class="text-capitalize">{{ $error }}</li>
	                  @endforeach
	              </ul>
	          </div>
	      @endif
	 		{{ Form::open(['method'=>'put','route'=>array('supplies.update',$supply->id),'class'=>'form-horizontal','id'=>'inventoryForm']) }}
			<ul class="breadcrumb">
				<li><a href="{{ url('inventory/item') }}">Inventory</a></li>
				<li><a href="{{ url('supplies') }}">Supplies</a></li>
				<li class="active">Create</li>
			</ul>
			<!-- item name -->
			<div class="form-group">
				<div class="col-sm-12">
				{{ Form::label('brand','Brand') }}
				{{ Form::text('brand',Input::old('brand'),[
					'class' => 'form-control',
					'placeholder' => 'Brand',
					'id' => 'brand'
				]) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
				{{ Form::label('itemtype','Type') }}
				{{ Form::select('itemtype',['Fetching all item types...'],Input::old('itemtype'),[
					'class' => 'form-control',
					'placeholder' => 'Item type',
					'id' => 'itemtype'
				]) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
				{{ Form::label('unit','Unit') }}
				{{ Form::text('unit',Input::old('unit'),[
					'class' => 'form-control',
					'placeholder' => 'Unit'
				]) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<button type="submit" value="create" name="action" id="submit" class="btn btn-lg btn-primary btn-block btn-flat btn-block"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Update </button>
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">
	$(document).ready(function(){

		init();

		function init(){
			$('#brand').val("{{ $supply->brand }}")
			$('#unit').val("{{ $supply->unit }}")
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: "get",
				url: "{{ url('get/inventory/item/type/supply') }}",
				dataType: 'json',
				success: function(response){
					var options = "";

					for(var ctr = 0;ctr < response.length;ctr++)
					{
						options += `<option value="`+response[ctr].id+`">
							`+response[ctr].name+`
						</option>`;
					}

					$('#itemtype').html("");
					$('#itemtype').append(options);
				}, 
				complete: function(){
					@if(Input::old('itemtype'))
					$('#itemtype').val("{{ Input::old('itemtype') }}")
					@else
					$('#itemtype').val("{{ $supply->itemtype_id }}")
					@endif
				}
			})
		}

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif

		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

		$('#page-body').show();
	})
</script>
@stop
