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
		<th> Property Number </th>
		<th> Location </th>
		<th> Date of Deployment </th>
		{{-- <th> Transferred By </th> --}}
	</thead>
	<tbody>
		@foreach($deployment as $deployment)
		<tr>
			<td class="col-sm-1">{{ $deployment->inventory->brand }} {{ $deployment->inventory->model}}</td>
			<td class="col-sm-1">{{ $deployment->propertynumber }}</td>
			<td class="col-sm-1">{{ $deployment->location }}</td>
			<td class="col-sm-1">{{ Carbon\Carbon::parse($deployment->deployment)->format('F d Y') }}</td>
			{{-- <td class="col-sm-1"></td> --}}
		</tr>
		@endforeach
		<tr>
			<td colspan="8" class="text-center">*** Nothing Follows ***</td>
		</tr>
	</tbody>
</table>
@stop