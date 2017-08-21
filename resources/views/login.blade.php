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
<div class="container-fluid" id="page-body" style="margin-top: 50px;margin-left: 30px;margin-right: 30px;">
  <div class="row">
    <div class="col-sm-offset-4 col-sm-4">
      <div class="panel panel-body panel-shadow">
        <div class="col-sm-12" id="loginPanel" style="padding: 20px" >
          <legend><h3 class="text-center text-primary">Log In</h3></legend>
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
        swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif

    @if( Session::has("error-message") )
        swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    $('#loginButton').submit(function(){
      return false;
    });

    $( "#loginForm" ).validate( {
      rules: {
        username: {
          required: true,
          minlength: 4,
        },
        password: {
          required: true,
          minlength: 8
        },
      },
      messages: {
        username: {
          required: "Please provide a username",
          minlength: "Your username must be at least 4 characters long"
        },
        password: {
          required: "Please provide a password",
          minlength: "Your password must be at least 8 characters long"
        },
      },
      errorElement: "em",
      errorPlacement: function ( error, element ) {
        // Add the `help-block` class to the error element
        error.addClass( "help-block" );

        // Add `has-feedback` class to the parent div.form-group
        // in order to add icons to inputs
        element.parents( ".form-group" ).addClass( "has-feedback" );

        if ( element.prop( "type" ) === "checkbox" ) {
          error.insertAfter( element.parent( "label" ) );
        } else {
          error.insertAfter( element );
        }

        // Add the span element, if doesn't exists, and apply the icon classes to it.
        if ( !element.next( "span" )[ 0 ] ) {
          $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
        }
      },
      success: function ( label, element ) {
        // Add the span element, if doesn't exists, and apply the icon classes to it.
        if ( !$( element ).next( "span" )[ 0 ] ) {
          $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
        }
      },
      submitHandler: function(form) {
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
            if(response.toString() == 'success'){
              $('#error-container').html(`
                <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <ul class="list-unstyled" id="list-error">
                      <li><span class="glyphicon glyphicon-ok"></span> You will be now redirected to Dashboard</li>
                    </ul>
                </div>`)
              window.location.href = "{{ url('login') }}"
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
      },
      highlight: function ( element, errorClass, validClass ) {
        $( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
        $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
      },
      unhighlight: function ( element, errorClass, validClass ) {
        $( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
        $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
      }
    } );

    $('#page-body').show();
  });

  $(document).ajaxStart(function(){
    $.LoadingOverlay("show");
  });

  $(document).ajaxStop(function(){
      $.LoadingOverlay("hide");
  });
</script>
@stop
