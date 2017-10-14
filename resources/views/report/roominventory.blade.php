@extends('layouts.report')
@section('title')
{{ (isset($title)) ? $title: '' }}
@stop
@section('report-content-heading')
{{ (isset($title)) ? $title: '' }}
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
		@foreach($roominventory as $roominventory)
		@foreach($roominventory as $inventory)
		@foreach($inventory as $inv)
		<tr>
			<td class="col-sm-1">{{ $inv->first()->itemtype }}</td>
			<td class="col-sm-1">{{ $inv->first()->brand }}</td>
			<td class="col-sm-1">{{ $inv->first()->model }}</td>
			<td class="col-sm-1">{{ $inv->first()->details }}</td>
			<td class="col-sm-1">{{ count($inv) }}</td>
		</tr>
		@endforeach
		@endforeach
		@endforeach
		<tr>
			<td colspan="6" class="text-center">*** Nothing Follows ***</td>
		</tr>
	</tbody>
</table>
@stop