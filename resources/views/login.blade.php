  @extends('layouts.master-plain')
@section('title')
Login
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar.main.default')
@stop
@section('style')
<link rel="stylesheet" href="{{ asset('css/style.css') }}"  />
<style>
  #return{
    text-decoration: none;
  }

  #return.hover{
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }

  a{
    text-decoration: none;
    display: block;
  }

  #page-body{
    display: none;
  }

  body{
        background-color: #F5F8FA;
  }
</style>
@stop
@section('script-include')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
@stop
@section('content')
<div class="container-fluid" id="page-body" style="margin-top: 50px">
  <div class="row">
    <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6">
      <div class="panel panel-body panel-shadow">
        <div class="col-sm-12" id="loginPanel" style="padding: 20px 20px 0px 20px" >
          <legend class=hidden-xs>
            <div class="row center-block" style="margin-bottom: 10px;">
              <div class="col-xs-4" style="padding-right:5px;">
                <img class=" img img-responsive pull-right" src="{{ asset('images/logo/ccis/ccis-logo-64.png') }}" style="width:64px;height: auto;"/>
              </div>
              <div class="col-xs-8" style="padding-left:5px;">
                <h4 class="text-muted pull-left">College of Computer and Information Sciences</h4>
              </div>
            </div>
          </legend>
          <div style="margin-top: 10px;">
            <div id="error-container"></div>
            {{ Form::open(array('class' => 'form-horizontal','id'=>'loginForm')) }}
            <div class="form-group">
              <div class="col-md-12">
                {{ Form::label('username','Username') }}
                {{ Form::text('username',Input::old('username'),[
                  'required',
                  'id'=>'username',
                  'class'=>'form-control',
                  'placeholder'=>'Username',
                  'id' => 'username'
                ]) }}
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
              {{ Form::label('Password') }}
              {{ Form::password('password',[
                  'required',
                  'id'=>'password',
                  'class'=>'form-control',
                  'placeholder'=>'Password',
              ]) }}
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                  <button type="submit" id="loginButton" data-loading-text="Logging in..." class="btn btn-md btn-primary btn-block" autocomplete="off">
                  Login
                </button>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12 text-center">
                <p class="text-muted" style="letter-spacing: 1px"> CCIS - LOO </p>
              </div>
            </div>
{{--             <a href="{{ route('reset') }}" class="text-center text-muted" type="button" role="button" style="text-decoration: none;"><small style="letter-spacing: 2px;">Forgot your password?</small></a> --}}
          {{ Form::close() }}
          </div>
        </div>
      </div>
    </div> <!-- centered  -->
  </div><!-- Row -->
</div><!-- Container -->
@stop
@section('script')
{{ HTML::script(asset('js/loadingoverlay.min.js')) }}
{{ HTML::script(asset('js/loadingoverlay_progress.min.js')) }}
<script>
  $(document).ready(function(){

    @if( Session::has("success-message") )
      $('#error-container').html(`
        <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <ul class="list-unstyled" id="list-error">
              <li><span class="glyphicon glyphicon-ok"></span> You will be now redirected to Dashboard</li>
            </ul>
        </div>`)
    @endif

    @if( Session::has("error-message") )
      $('#error-container').html(`
        <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <ul class="list-unstyled" id="list-error">
              <li><span class="glyphicon glyphicon-ok"></span> You need to login before accessing the page</li>
            </ul>
        </div>`)
    @endif

    $("#loginForm").submit(function(e){
        e.preventDefault();
        // do other things for a valid form
        var $btn = $('#loginButton').button('loading')
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'post',
          url:'{{ url("login") }}',
          data:{
            'username':$('#username').val(),
            'password':$('#password').val()
          },
          success:function(response){
            $btn.button('reset')
            $('#password').val('')
            if(response.toString() == 'success'){
              $('#error-container').html(`
                <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <ul class="list-unstyled" id="list-error">
                      <li><span class="glyphicon glyphicon-ok"></span> You will be now redirected to Dashboard</li>
                    </ul>
                </div>`)
              window.location.href = '{{ url('login') }}'
            }else{
              $('#error-container').html(`
                <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <ul class="list-unstyled" id="list-error">
                      <li><span class="glyphicon glyphicon-remove"></span> Credentials submitted does not exists</li>
                    </ul>
                </div>`)
            }
          },
          error:function(response){
            $btn.button('reset')
              $('#error-container').html(`
                <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <ul class="list-unstyled" id="list-error">
                      <li><span class="glyphicon glyphicon-remove"></span> Problem occurred while sending your data to the servers</li>
                    </ul>
                </div>`)
          }
        });
    })

    $(document).ajaxStart(function(){
      $.LoadingOverlay("show");
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

    $('#page-body').show();
  });
</script>
@stop
