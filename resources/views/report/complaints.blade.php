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
		<th> Ticket ID </th>
		<th> Tag </th>
		<th> Description </th>
		<th> Date </th>
		<th> Author </th>
		<th> Status </th>
	</thead>
	<tbody>
		@foreach($ticket as $ticket)
		<tr>
			<td class="col-sm-1">{{ $ticket->id }}</td>
			<td class="col-sm-1">{{ $ticket->tag }}</td>
			<td class="col-sm-1">{{ $ticket->details}}</td>
			<td class="col-sm-1">{{ Carbon\Carbon::parse($ticket->date)->format('F d Y') }}</td>
			<td class="col-sm-1">{{ $ticket->staffassigned }}</td>
			<td class="col-sm-1">{{ $ticket->status }}</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="8" class="text-center">*** Nothing Follows ***</td>
		</tr>
	</tbody>
</table>
@stop