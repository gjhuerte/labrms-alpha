@extends('layouts.master-blue')
@section('title')
Dashboard
@stop
@section('navbar')
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/timetable.css')) }}
<style>
  .panel-shadow{
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
</style>
@stop
@section('content')
@include('modal.ticket.create')
<!--
<div class="container-fluid">
	<div class="col-md-3">
		<ul class="list-group panel panel-default">
			<div class="panel-heading">
			    Notification  <span class="label label-primary" id="notification-count" val=0>0</span> <p class="text-success pull-right">Active</p>
			</div>
			<li class="list-group-item">
			List Item
			</li>
		</ul>
	</div>
	<div class="col-md-6">
		<ul class="panel panel-default">
			<div class="panel-body" id="content">
            <button class="btn btn-info" data-toggle="modal" data-target="#generateTicketModal"><span class="glyphicon glyphicon-share-alt"></span> View all</button>
            <button class="btn btn-default" data-toggle="modal" data-target="#generateTicketModal"><span class="glyphicon glyphicon-share-alt"></span> Transfer Ticket</button>
    		    <button class="btn btn-primary" data-toggle="modal" data-target="#generateTicketModal"><span class="glyphicon glyphicon-plus"></span> Generate Ticket</button>
			</div>
		</ul>
	</div>
</div>

-->
{{-- <div class="container-fluid">
	<div class="panel panel-default">
		<div class="panel-body">
			<legend><h3 class="text-muted">Laboratory Schedule</h3></legend>
			<div class="timetable"></div>
		</div>
	</div>
</div> --}}
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/timetable.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {
		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif
		setInterval(function(){
			var count = $('#notification-count').val();
			count++;
			$('#notification-count').val(count);
			$('#notification-count').html(count);
		},1000);

		var timetable = new Timetable();
		timetable.setScope(7, 21); // optional, only whole hours between 0 and 23
		timetable.addLocations(['Monday', 'Tuesday', 'Wednesday', 'Thursday','Friday','Saturday','Sunday']);
		// timetable.addEvent('Frankadelic', 'Nile', new Date(2015,7,17,10,45), new Date(2015,7,17,12,30));
		var renderer = new Timetable.Renderer(timetable);
		renderer.draw('.timetable'); // any css selector
	});
</script>
@stop
