<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>@yield('title')</title>

    <!-- Bootstrap -->
    {{ HTML::style(asset('css/jquery-ui.css')) }}
    {{ HTML::style(asset('css/bootstrap.min.css')) }}
    {{ HTML::style(asset('css/sweetalert.css')) }}
    {{ HTML::style(asset('css/dataTables.bootstrap.min.css')) }}
    {{ HTML::style(asset('css/buttons.bootstrap.min.css')) }}
	<style>
		body{
			background-color:white;
			font-family: "Times New Roman"	;
		}

		.header-text-lg{
			font-size: 20px;
			letter-spacing: 0.5px;
		}

		.header-text-md{
			font-size: 17px;
			letter-spacing: 1px;
		}

		h3, .no-space{
			padding:0px;
			margin:0px;
		}

		.header{
			padding: 5px;
			/*position:fixed;*/
		}

		.content{
			padding:5px;
		}

		.footer{
		}

		.tight
		{
		    width: 1px;
		    white-space: nowrap;
		}
	</style>
    @yield('style-include')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('style')
    
    {{ HTML::script(asset('js/jquery.min.js')) }}
    {{ HTML::script(asset('js/jquery-ui.js')) }}
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    {{ HTML::script(asset('js/bootstrap.min.js')) }}
    {{ HTML::script(asset('js/sweetalert.min.js')) }}
    {{ HTML::script(asset('js/jquery.dataTables.min.js')) }}
    {{ HTML::script(asset('js/dataTables.bootstrap.min.js')) }}
    @yield('script-include')
  </head>
  <body id="page-top">
    @yield('navbar')
	<div class="container-fluid">
		<div class="row">
			<div class="header">
				<div class="row">
					<legend style="padding:10px;">
						<div style="">
							<div class="clearfix"></div>
							<img src="{{ asset(config('app.company.image','img/logo.png')) }}" class="img img-responsive img-circle pull-left" style="height:86px;" />
						</div>
						<div style="margin-left:100px;">
							<h3 class="header-text-lg text-left">{{ config('app.company.header','Company Header') }}</h3>
							<h3 class="header-text-md text-left">{{ config('app.company.subheader','Company Sub Header') }}</h3>
							<h3 class="header-text-lg text-left">{{ config('app.company.department','Company Department') }}</h3>
							<h3 class="header-text-lg text-left">{{ config('app.company.subdepartment','Company Sub Dept') }}</h3>
						</div>
					</legend>
				</div>
			</div>
			<div class="content">
				<div class="content-header">
					<h4 class="no-space text-center">@yield('report-content-heading')</h4>
					<h4 class="no-space text-center">Laboratory Resource Management System</h4>
				</div>
				<br />
				@yield('report-content')
				</table>
			</div>
			<div class="footer">
				<div class="footer-border">
					<table class="table table-bordered">
						<tr>
							<td colspan=3>Prepared By:</td>
							<td colspan=1>Assessed By:</td>
						</tr>
						<tr>
							<td class="col-sm-1 text-center" style="padding:20px;">
								<legend style="font-size: 16px;margin-bottom:5px;">
									{{ Auth::user()->firstname }} {{ Auth::user()->middlename }} {{ Auth::user()->lastname }}
								</legend>
								@if(Auth::user()->accesslevel == 0)
								Laboratory Head
								@elseif(Auth::user()->accesslevel == 1)
								Laboratory Assistant
								@elseif(Auth::user()->accesslevel == 2)
								Laboratory Staff
								@endif
							</td>
							<td class="col-sm-1" style="padding:20px;"></td>
							<td class="col-sm-1" style="padding:20px;"></td>
							<td class="col-sm-1 text-center" style="padding:20px;">
								<legend style="font-size: 16px;margin-bottom:5px;">
									{{ $assessor_name }}
								</legend>
									{{ $assessor_position }}
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
    @yield('script')
  </body>
</html>