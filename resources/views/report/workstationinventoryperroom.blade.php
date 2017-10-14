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
		<th> Workstation No. </th>
		<th> Property Number </th>
		<th> Specification </th>
		<th> Mouse </th>
		<th> Keyboard </th>
		<th> Display </th>
	</thead>
	<tbody>
		@foreach($workstation as $workstation)
		<tr>
			<td class="col-sm-1">{{ $workstation->name }}</td>
			<td class="col-sm-1">{{ $workstation->systemunit_propertynumber }}</td>
			<td class="col-sm-1">{{ $workstation->systemunit_specs }}</td>
			<td class="col-sm-1">{{ $workstation->mouse }}</td>
			<td class="col-sm-1">{{ $workstation->keyboard_brand }}</td>
			<td class="col-sm-1">{{ $workstation->monitor_specs }}</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="6" class="text-center">*** Nothing Follows ***</td>
		</tr>
	</tbody>
</table>
@stop