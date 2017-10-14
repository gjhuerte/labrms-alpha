@extends('layouts.master-blue')
@section('title')
Inventory
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style-include')
{{ HTML::style(asset('css/jquery-ui.css')) }}
{{ HTML::style(asset('css/jquery.sidr.light.min.css')) }}
{{ HTML::style(asset('css/sidr-style.min.css')) }}
@stop
@section('script-include')
{{ HTML::script(asset('js/jquery.sidr.min.js')) }}
{{ HTML::script(asset('js/jquery.hideseek.min.js')) }}
<script src="{{ asset('js/jQuery.succinct.min.js') }}"></script>
@stop
@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>

	#page-body,#profile,#view{
		display: none;
	}

	a > hover{
		text-decoration: none;
	}

	th , tbody{
		text-align: center;
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

</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="panel panel-body table-responsive">
		<legend><h3 class="text-muted">Advance Search - Item Inventory</h3></legend>
		<div class="col-md-4">
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
			{{ Form::open(['method'=>'post','url'=>('inventory/item/search'),'class'=>'form-horizontal']) }}
			<div class="form-group">
				<div class="col-sm-3">
					{{ Form::label('Keywords') }}
				</div>
				<div class="col-sm-9">
					{{ Form::text('keyword',Input::old('keyword'),[
						'class' => 'form-control',
						'placeholder' => 'Enter keywords here ...'
					]) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3">
					{{ Form::label('Total') }}
				</div>
				<div class="col-sm-9">
					{{ Form::number('total',Input::old('total'),[
						'class' => 'form-control',
						'placeholder' => 'Enter quantity here ....'
					]) }}
					<input type="checkbox" name="include-total" checked /> Include as Filter
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3">
					{{ Form::label('Profiled') }}
				</div>
				<div class="col-sm-9">
					{{ Form::number('profiled',Input::old('profiled'),[
						'class' => 'form-control',
						'placeholder' => 'Enter profiled quantity here ....'
					]) }}
					<input type="checkbox" name="include-profiled" checked /> Include as Filter
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3">
					{{ Form::label('Brand') }}
				</div>
				<div class="col-sm-9">
					{{ Form::select('brand',$brand,Input::old('brand'),[
						'class' => 'form-control',
					]) }}
					<input type="checkbox" name="include-brand" checked /> Include as Filter
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3">
					{{ Form::label('Model') }}
				</div>
				<div class="col-sm-9">
					{{ Form::select('model',$model,Input::old('model'),[
						'class' => 'form-control',
					]) }}
					<input type="checkbox" name="include-model" checked /> Include as Filter
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3">
					{{ Form::label('Item Type') }}
				</div>
				<div class="col-sm-9">
					{{ Form::select('itemtype',$itemtype,Input::old('itemtype'),[
						'class' => 'form-control',
					]) }}
					<input type="checkbox" name="include-itemtype" checked /> Include as Filter
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-group">
				<div class="col-sm-12">
					<button type=submit class="btn btn-primary btn-sm pull-right">Search</button>
				</div>
			</div>
			{{ Form::close() }}
		</div>
		<div class="col-md-8" style="border:solid 1px #e5e5e5">
			<h4>Result:</h4>
			<table class="table table-hover table-striped table-bordered table-condensed" id="inventoryTable">
				<thead>
					<th>ID</th>
					<th>Model</th>
					<th>Brand</th>
					<th>Type</th>
					<th>Details</th>
					<th>Warranty</th>
					<th>Unit</th>
					<th>Quantity</th>
					<th>Unprofiled</th>
				</thead>
				<tbody>
				@if(isset($inventory))
					@forelse($inventory as $inventory)
					<tr>
						<td>{{ $inventory->id }}</td>
						<td>{{ $inventory->model }}</td>
						<td>{{ $inventory->brand }}</td>
						<td>{{ $inventory->type }}</td>
						<td>{{ $inventory->details }}</td>
						<td>{{ $inventory->warranty }}</td>
						<td>{{ $inventory->unit }}</td>
						<td>{{ $inventory->quantity }}</td>
						<td>{{ $inventory->quantity-$inventory->profiled }}</td>
					</tr>
					@empty
					@endforelse
				@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/jquery-ui.js')) }}
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

		$('#inventoryTable').DataTable();

		$('#page-body').show();
	} );
</script>
@stop
