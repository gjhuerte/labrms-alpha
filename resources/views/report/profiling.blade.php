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
		<th> Equipment </th>
		<th> Description </th>
		<th> Date of Profiling </th>
		<th> Number of Profiled </th>
		<th> Profiled By </th>
	</thead>
	<tbody>
		@foreach($profiling as $roominventory)
		@foreach($roominventory as $inventory)
		@foreach($inventory as $invent)
		@foreach($invent as $inv)
		<tr>
			<td class="col-sm-1">{{ $inv->first()->brand }} {{ $inv->first()->model }}</td>
			<td class="col-sm-1">{{ $inv->first()->details }}</td>
			<td class="col-sm-1">
				{{ Carbon\Carbon::parse($inv->first()->created_at)->format('F d Y') }}
			</td>
			<td class="col-sm-1">{{ count($inv) }}</td>
			<td class="col-sm-1">{{ $inv->first()->profiled_by }}</td>
		</tr>
		@endforeach
		@endforeach
		@endforeach
		@endforeach
		<tr>
			<td colspan="6" class="text-center">*** Nothing Follows ***</td>
		</tr>
	</tbody>
</table>
@stop