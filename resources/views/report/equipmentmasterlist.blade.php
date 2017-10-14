@extends('layouts.report')
@section('title')
Equipment Masterlist - As of {{ Carbon\Carbon::now()->toFormattedDateString() }}
@stop
@section('report-content-heading')
Equipment Masterlist - As of {{ Carbon\Carbon::now()->toFormattedDateString() }}
@stop
@section('report-content')
<table class="table table-striped table-bordered">
	<thead>
		<th> Item Type </th>
		<th> Brand </th>
		<th> Model </th>
		<th> Specification </th>
		<th> Quantity </th>
	</thead>
	<tbody>
		@foreach($inventory as $inventory)
		<tr>
			<td class="col-sm-1">{{ $inventory->itemtype->name }}</td>
			<td class="col-sm-1">{{ $inventory->brand }}</td>
			<td class="col-sm-1">{{ $inventory->model }}</td>
			<td class="col-sm-1">{{ $inventory->details }}</td>
			<td class="col-sm-1">{{ $inventory->quantity }}</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="5" class="text-center">*** Nothing Follows ***</td>
		</tr>
	</tbody>
</table>
@stop