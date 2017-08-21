@extends('layouts.master-blue')
@section('title')
Create
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/jquery.timepicker.css')) }}
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
        <legend><h3 class="text-muted">Create Schedule</h3></legend>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('schedule') }}">Schedule</a>
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
        {{ Form::open(array('method'=>'post','route'=>'schedule.store','class' => 'form-horizontal')) }}
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('subject','Subject') }}
            {{ Form::text('subject',Input::old('subject'),[
              'required',
              'class'=>'form-control',
              'placeholder'=>'Subject'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('faculty','Faculty in-charge') }}
            {{ Form::text('faculty',Input::old('faculty'),[
              'class'=>'form-control',
              'placeholder'=>'Faculty in-charge'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('section','Course, Year & Section') }}
            {{ Form::text('section',Input::old('section'),[
              'class'=>'form-control',
              'placeholder'=>'Course Year-Section'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('section','Course, Year & Section') }}
            {{ Form::text('section',Input::old('section'),[
              'class'=>'form-control',
              'placeholder'=>'Course Year-Section'
            ]) }}
          </div>
        </div>
        <div class="form-group" id="timerange">
          <div class="col-md-6">
            {{ Form::label('timestart','Time Start') }}
            {{ Form::text('timestart',Input::old('timestart'),[
              'class'=>'form-control time',
              'placeholder'=>'Time Start'
            ]) }}
          </div>
          <div class="col-md-6">
            {{ Form::label('timeend','Time End') }}
            {{ Form::text('timeend',Input::old('timeend'),[
              'class'=>'form-control time',
              'placeholder'=>'Time End'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::submit('Create',[
              'class'=>'btn btn-lg btn-primary btn-block',
              'name' => 'create'
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
{{ HTML::script(asset('js/datepair.min.js')) }}
{{ HTML::script(asset('js/jquery.datepair.min.js')) }}
{{ HTML::script(asset('js/jquery.timepicker.min.js')) }}
<script>
  $(document).ready(function(){
    @if( Session::has("success-message") )
        swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif
    @if( Session::has("error-message") )
        swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    // initialize input widgets first
    $('#timerange > .col-md-6 > .form-control').timepicker({
        'showDuration': true
    });
    
    $('#page-body').show();
  });
</script>
@stop
