@if(Auth::check())
	@include('layouts.navbar.laboffice.default')
@else
	@include('layouts.navbar.main.default')
@endif