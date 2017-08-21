@extends('layouts.master-blue')
@section('title')
Inventory | Item
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('content')
	<h2 class="text-muted text-center">
		No item to display
	</h2>
@stop
