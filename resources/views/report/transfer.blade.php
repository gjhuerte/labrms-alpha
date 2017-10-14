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
		<th> Old Location </th>
		<th> New Location </th>
		<th> Date Transferred </th>
		{{-- <th> Transferred By </th> --}}
	</thead>
	<tbody>
		@foreach($transfer as $transfer)
		<tr>
			<td class="col-sm-1">{{ $transfer->equipment }}</td>
			<td class="col-sm-1">{{ $transfer->propertynumber }}</td>
			<td class="col-sm-1">{{ $transfer->oldlocation }}</td>
			<td class="col-sm-1">{{ $transfer->newlocation }}</td>
			<td class="col-sm-1">{{ Carbon\Carbon::parse($transfer->datetransferred)->format('F d Y') }}</td>
			{{-- <td class="col-sm-1"></td> --}}
		</tr>
		@endforeach
		<tr>
			<td colspan="8" class="text-center">*** Nothing Follows ***</td>
		</tr>
	</tbody>
</table>
@stop