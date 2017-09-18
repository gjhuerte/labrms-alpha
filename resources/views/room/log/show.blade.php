@extends('layouts.master-blue')
@section('title')
Room Log
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
  #page-body{
    display: none;
  }

</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
  <div class="col-md-12">
    <div class="panel panel-body table-responsive">
      <legend class="text-muted"><h3>Room Log</h3></legend>
      <ul class="breadcrumb">
        <li><a href="{{ url('room/log') }}">Room</a></li>
        <li class="active">{{ $room->name }}</li>
      </ul>
      <table class="table table-hover table-bordered" id="itemProfileTable" cellspacing="0" width="100%">
        <thead>
          <tr rowspan="2">
              <th class="text-left" colspan="4">Name:  
                <span style="font-weight:normal">{{ $room->name }}</span> 
              </th>
              <th class="text-left" colspan="4">Description:  
                <span style="font-weight:normal">{{ $room->description }}</span> 
              </th>
          </tr>
          <tr rowspan="2">
              <th class="text-left" colspan="4">Category:  
                <span style="font-weight:normal">{{ $room->category }}</span>  
              </th>
              <th class="text-left" colspan="4">
                <span style="font-weight:normal"></span> 
              </th>
          </tr>
          <tr rowspan="2">
              <th class="text-center" colspan="12">Log</th>
          </tr>
          <tr>
            <th>Ticket ID</th>
            <th>Ticket Type</th>
            <th>Name</th>
            <th>Details</th>
            <th>Author</th>
            <th>Staff Assigned</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        @forelse($room->ticket as $ticket)
          <tr>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->tickettype }}</td>
            <td>{{ $ticket->ticketname }}</td>
            <td>{{ $ticket->details }}</td>
            <td>{{ $ticket->author }}</td>
            <td>{{ $ticket->user->firstname }} {{ $ticket->user->lastname }}</td>
            <td>{{ $ticket->status }}</td>
          </tr>
        @empty
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@stop
@section('script')
<script>
  $(document).ready(function(){

    $('#itemProfileTable').DataTable();
    @if( Session::has("success-message") )
      swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif
    @if( Session::has("error-message") )
      swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    $('#page-body').show()
  })
</script>
@stop
