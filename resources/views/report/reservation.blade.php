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
		<th> Reservation Date </th>
		<th> Requestor </th>
		<th> Equipment </th>
		<th> Date of Use </th>
		<th> Time of Use </th>
		<th> Faculty in-charge </th>
		<th> Status </th>
		<th> Room </th>
	</thead>
	<tbody>
		@foreach($reservation as $reservation)
		<tr>
			<td class="col-sm-1">{{ Carbon\Carbon::parse($reservation->created_at)->format('F d Y') }}</td>
			<td class="col-sm-1">
				{{ $reservation->firstname . " " . $reservation->middlename . " " . $reservation->lastname }}
			</td>
			<td class="col-sm-1">{{ $reservation->itemtype }}</td>
			<td class="col-sm-1">{{ Carbon\Carbon::parse($reservation->timein)->format('F d Y') }}</td>
			<td class="col-sm-1">
				{{ Carbon\Carbon::parse($reservation->timein)->format('H:i a') . " - " . Carbon\Carbon::parse($reservation->timeout)->format('H:i a') }}
			</td>
			<td class="col-sm-1">{{ $reservation->facultyincharge }}</td>
			<td class="col-sm-1">{{ $reservation->remark }}</td>
			<td class="col-sm-1">{{ $reservation->location }}</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="8" class="text-center">*** Nothing Follows ***</td>
		</tr>
	</tbody>
</table>
@stop