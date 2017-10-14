@extends('layouts.master-blue')
@section('title')
Lost And Found
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/datepicker.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
  #page-body{
    display: none;
  }
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
  <div class="row">
    <div class="col-sm-offset-3 col-sm-6">
      <div class="col-md-12 panel panel-body " style="padding: 25px;padding-top: 10px;">
        <legend><h3 class="text-muted">Lost And Found</h3></legend>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('lostandfound') }}">Lost And Found</a>
          </li>
          <li class="active">Create</li>
        </ol>
        @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <ul style='margin-left: 10px;'>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{ Form::open(array('method'=>'post','route'=>'lostandfound.store','class' => 'form-horizontal','id'=>'semesterForm')) }}
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('identifier','Item Identifier') }}
            {{ Form::text('identifier',Input::old('identifier'),[
              'required',
              'class'=>'form-control',
              'placeholder'=>'Unique description that will identify the item'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('description','Description') }}
            {{ Form::textarea('description',Input::old('description'),[
              'required',
              'class'=>'form-control',
              'placeholder'=>'Other description'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('datefound','Date Found') }}
            {{ Form::text('datefound',Input::old('datefound'),[
              'data-language'=>"en",
              'id' => 'datefound',
              'class'=>'form-control',
              'readonly',
              'style'=>'background-color: #ffffff '
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::submit('Create',[
              'class'=>'btn btn-lg btn-primary btn-block',
              'name' => 'create',
              'id' => 'create'
            ]) }}
          </div>
        </div>
      {{ Form::close() }}
      </div>
    </div> <!-- centered  -->
  </div><!-- Row -->
</div><!-- Container -->
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/datepicker.min.js')) }}
{{ HTML::script(asset('js/datepicker.en.js')) }}
<script>
  $(document).ready(function(){

    $("#datefound").datepicker({
      language: 'en',
      showOtherYears: false,
      todayButton: true,
      maxDate: new Date(),
      autoClose: true,
      onSelect: function(){
        $('#datefound').val(moment($('#datefound').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
      }
    });

    $("#datefound").val(moment('{{ Carbon\Carbon::now() }}').format('MMMM DD, YYYY'));

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
