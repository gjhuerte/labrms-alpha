@extends('layouts.master-blue')
@section('title')
Create
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/font-awesome.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
  #page-body{
    display: none;
  }

  .rating {
    unicode-bidi: bidi-override;
    direction: rtl;
  }
  .rating > span {
    display: inline-block;
    position: relative;
    width: 1.1em;
  }
  .rating > span:hover:before,
  .rating > span:hover ~ span:before {
     content: "\2605";
     position: absolute;
  }
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
  <div class="row">
    <div class="col-sm-offset-3 col-sm-6">
      <div class="col-md-12 panel panel-body " style="padding: 25px;padding-top: 10px;">
        <legend class='text-muted'><h3>Reservation Purpose</h3></legend>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('purpose') }}">Reservation Purpose</a>
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
        {{ Form::open(array('method'=>'post','route'=>'purpose.store','class' => 'form-horizontal')) }}
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('title','Title') }}
            {{ Form::text('title',Input::old('title'),[
              'required',
              'class'=>'form-control',
              'placeholder'=>'Reservation Purpose Title'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('Points') }}
            <div class="list-group">
              <a class="list-group-item" href="#">
                <div class="row">
                  <div class="col-sm-1">
                    <input type="radio" name="points" value="4" checked/>
                  </div>
                  <div class="col-sm-3">
                    <i class="fa fa-star fa-fw" aria-hidden="true"></i>
                    <i class="fa fa-star fa-fw" aria-hidden="true"></i>
                    <i class="fa fa-star fa-fw" aria-hidden="true"></i>
                    <i class="fa fa-star fa-fw" aria-hidden="true"></i>
                  </div>
                  <span class="col-sm-8">
                    {{ Form::label('4 points') }}
                  </span>
                  <span class="col-sm-offset-4 col-sm-8">
                    First Highest Prioritization: General Assembly, Seminar, College Tutorial
                  </span>
                </div>
              </a>
              <a class="list-group-item" href="#">
                <div class="row">
                  <div class="col-sm-1">
                    <input type="radio" name="points" value="3" checked/>
                  </div>
                  <div class="col-sm-3">
                    <i class="fa fa-star fa-fw" aria-hidden="true"></i>
                    <i class="fa fa-star fa-fw" aria-hidden="true"></i>
                    <i class="fa fa-star fa-fw" aria-hidden="true"></i>
                  </div>
                  <span class="col-sm-8">
                    {{ Form::label('3 points') }}
                  </span>
                  <span class="col-sm-offset-4 col-sm-8">
                    Second Highest Prioritization: (Regular Class) Class Presentation, Class Activity, Oral Defense
                  </span>
                </div>
              </a>
              <a class="list-group-item" href="#">
                <div class="row">
                  <div class="col-sm-1">
                    <input type="radio" name="points" value="2" checked/>
                  </div>
                  <div class="col-sm-3">
                    <i class="fa fa-star fa-fw" aria-hidden="true"></i>
                    <i class="fa fa-star fa-fw" aria-hidden="true"></i>
                  </div>
                  <span class="col-sm-8">
                    {{ Form::label('2 points') }}
                  </span>
                  <span class="col-sm-offset-4 col-sm-8">
                    Third Highest Prioritization: Make-up clas, Tutorial Class by the Faculty
                  </span>
                </div>
              </a>
              <a class="list-group-item" href="#">
                <div class="row">
                  <div class="col-sm-1">
                    <input type="radio" name="points" value="1" checked/>
                  </div>
                  <div class="col-sm-3">
                    <i class="fa fa-star fa-fw" aria-hidden="true"></i>
                  </div>
                  <span class="col-sm-8">
                    {{ Form::label('1 point') }}
                  </span>
                  <span class="col-sm-offset-4 col-sm-8">
                    Fourth Highest Prioritization: Co-curricular Activities
                  </span>
                </div>
              </a>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('description','Description') }}
            {{ Form::textarea('description',Input::old('description'),[
              'required',
              'class'=>'form-control',
              'placeholder'=>'Reservation Purpose Additional Description'
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
<script>
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
