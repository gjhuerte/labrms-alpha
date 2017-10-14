@extends('layouts.master-blue')
@section('title')
Profile
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
<style>
	#page-body{
		display: none;
	}

	.panel{
		border-radius: 0px;
	}

	.line-either-side {
		overflow: hidden;
		text-align: center;
	}
	.line-either-side:before,
	.line-either-side:after {
		background-color: #e5e5e5;
		content: "";
		display: inline-block;
		height: 1px;
		position: relative;
		vertical-align: middle;
		width: 50%;
	}
	.line-either-side:before {
		right: 0.5em;
		margin-left: -50%;
	}
	.line-either-side:after {
		left: 0.5em;
		margin-right: -50%;
	}

	.twPc-div {
    background: #fff none repeat scroll 0 0;
    border: 1px solid #e1e8ed;
    border-radius: 6px;
    height: 200px;
    max-width: 340px; // orginal twitter width: 290px;
	}
	.twPc-bg {
	    background-image: url("https://pbs.twimg.com/profile_banners/50988711/1384539792/600x200");
	    background-position: 0 50%;
	    background-size: 100% auto;
	    border-bottom: 1px solid #e1e8ed;
	    border-radius: 4px 4px 0 0;
	    height: 95px;
	    width: 100%;
	}
	.twPc-block {
	    display: block !important;
	}
	.twPc-button {
	    margin: -35px -10px 0;
	    text-align: right;
	    width: 100%;
	}
	.twPc-avatarLink {
	    background-color: #fff;
	    border-radius: 6px;
	    display: inline-block !important;
	    float: left;
	    margin: -30px 5px 0 8px;
	    max-width: 100%;
	    padding: 1px;
	    vertical-align: bottom;
	}
	.twPc-avatarImg {
	    border: 2px solid #fff;
	    border-radius: 7px;
	    box-sizing: border-box;
	    color: #fff;
	    height: 72px;
	    width: 72px;
	}
	.twPc-divUser {
	    margin: 5px 0 0;
	}
	.twPc-divName {
	    font-size: 18px;
	    font-weight: 700;
	    line-height: 21px;
	}
	.twPc-divName a {
	    color: inherit !important;
	}
	.twPc-divStats {
	    margin-left: 11px;
	    padding: 10px 0;
	}
	.twPc-Arrange {
	    box-sizing: border-box;
	    display: table;
	    margin: 0;
	    min-width: 100%;
	    padding: 0;
	    table-layout: auto;
	}
	ul.twPc-Arrange {
	    list-style: outside none none;
	    margin: 0;
	    padding: 0;
	}
	.twPc-ArrangeSizeFit {
	    display: table-cell;
	    padding: 0;
	    vertical-align: top;
	}
	.twPc-ArrangeSizeFit a:hover {
	    text-decoration: none;
	}
	.twPc-StatValue {
	    display: block;
	    font-size: 18px;
	    font-weight: 500;
	    transition: color 0.15s ease-in-out 0s;
	}
	.twPc-StatLabel {
	    color: #8899a6;
	    font-size: 10px;
	    letter-spacing: 0.02em;
	    overflow: hidden;
	    text-transform: uppercase;
	    transition: color 0.15s ease-in-out 0s;
	}
</style>
@stop
@section('content')
<div class="container-fluid">
	<div class="col-sm-offset-3 col-sm-6">
	    <div class="panel panel-body">
	        <h4 class="line-either-side text-muted" id="myModalLabel">More About {{ Auth::user()->firstname }}</h4>
	        <center>
	        <img 
	          @if(Auth::user()->accesslevel == 0)
	          src="{{ asset('images/logo/LabHead/labhead-icon-64.png') }}"
	          @elseif(Auth::user()->accesslevel == 1)
	          src="{{ asset('images/logo/LabAssistant/assistant-logo-64.png') }}"
	          @elseif(Auth::user()->accesslevel == 2)
	          src="{{ asset('images/logo/LabStaff/staff-logo-64.png') }}"
	          @elseif(Auth::user()->accesslevel == 3)
	          src="{{ asset('images/logo/Student/student-logo-64.png') }}"
	          @elseif(Auth::user()->accesslevel == 4)
	          src="{{ asset('images/logo/Student/student-logo-64.png') }}"
	          @endif name="aboutme" width="140" height="140" border="0" class="img-circle"></a>
	        <h3 class="text-muted">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }} <small>
	        	@if(Auth::user()->accesslevel == 0)
	        	Laboratory Head
	        	@elseif(Auth::user()->accesslevel == 1)
	        	Laboratory Assistant
	        	@elseif(Auth::user()->accesslevel == 2)
	        	Laboratory Staff
	        	@elseif(Auth::user()->accesslevel == 3)
	        	Faculty
	        	@elseif(Auth::user()->accesslevel == 4)
	        	Student
	            @endif</small></h3>
	        <span><strong>Access Level: </strong></span>
	        	@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1)
	            <span class="label label-warning">Inventory</span>
	            <span class="label label-info">Information Systems</span>
	            <span class="label label-primary">Reservation (no 3 day rule)</span>
	            <span class="label label-success">Ticketing (reopen tickets)</span>
	        	@elseif(Auth::user()->accesslevel == 2)
	            <span class="label label-warning">Inventory</span>
	            <span class="label label-info">Ticketing</span>
	            <span class="label label-info">Reservation</span>
	        	@elseif(Auth::user()->accesslevel == 3||Auth::user()->accesslevel == 4)
	            <span class="label label-warning">Reservation</span>
	            <span class="label label-info">Complaints</span>
	            @endif
	        </center>
	        <hr>
	        <center>
			<div class="twPc-divStats">
				<ul class="twPc-Arrange">
						<li class="twPc-ArrangeSizeFit">
							<a href="https://twitter.com/mertskaplan/following" title="885 Following">
								<span class="twPc-StatLabel twPc-block">Approved Reservation</span>
								<span class="twPc-StatValue">{{ $approved }}</span>
							</a>
						</li>
						<li class="twPc-ArrangeSizeFit">
							<a href="https://twitter.com/mertskaplan/following" title="885 Following">
								<span class="twPc-StatLabel twPc-block">Assigned Task</span>
								<span class="twPc-StatValue">{{ $assigned }}</span>
							</a>
						</li>
						<li class="twPc-ArrangeSizeFit">
							<a href="https://twitter.com/mertskaplan/following" title="885 Following">
								<span class="twPc-StatLabel twPc-block">Total Complaints</span>
								<span class="twPc-StatValue">{{ $complaints }}</span>
							</a>
						</li>
				</ul>
			</div>
	        <p class="text-left text-muted"><strong>Bio: </strong><br>
	            I am {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}. You can contact me through my email address, if I have one, {{ Auth::user()->email }} or through my cellphone number {{ Auth::user()->cellno }}. I have the priviledge of being a
	        	@if(Auth::user()->accesslevel == 0)
	        	Laboratory Head
	        	@elseif(Auth::user()->accesslevel == 1)
	        	Laboratory Assistant
	        	@elseif(Auth::user()->accesslevel == 2)
	        	Laboratory Staff
	        	@elseif(Auth::user()->accesslevel == 3)
	        	Faculty
	        	@elseif(Auth::user()->accesslevel == 4)
	        	Student
	            @endif
	             and currently a {{ Auth::user()->type }} of this organization.  </p>
	        <br>
	        </center>
	    </div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function(){
		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif
		$('#page-body').show();
	});
</script>
@stop
