@extends('layouts.master-blue')
@section('title')
Semester
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
        <legend><h3 class="text-muted">Semester Creation</h3></legend>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('semester') }}">Semester</a>
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
        {{ Form::open(array('method'=>'post','route'=>'semester.store','class' => 'form-horizontal','id'=>'semesterForm')) }}
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('academicyear','Academic Year') }}
            {{ Form::select('academicyear',[],null,[
              'id' => 'academicyear',
              'class'=>'form-control'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('name','Semester') }}
            {{ Form::text('name',Input::old('name'),[
              'required',
              'class'=>'form-control',
              'placeholder'=>'Input the semester. Example 1st,2nd,3rd'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('start','Semester Start') }}
            {{ Form::text('start',Input::old('start'),[
              'data-language'=>"en",
              'id' => 'start',
              'class'=>'form-control',
              'readonly',
              'style'=>'background-color: #ffffff '
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('end','End of Semester') }}
            {{ Form::text('end',Input::old('end'),[
              'data-language'=>"en",
              'id' => 'end',
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

    $("#start").datepicker({
      language: 'en',
      showOtherYears: false,
      todayButton: true,
      autoClose: true,
      onSelect: function(){
        $('#start').val(moment($('#start').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
      }
    });

    $("#end").datepicker({
      language: 'en',
      showOtherYears: false,
      todayButton: true,
      autoClose: true,
      onSelect: function(){
        $('#end').val(moment($('#end').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
      }
    });

    $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'get',
      url: "{{ url('academicyear') }}",
      dataType: 'json',
      success: function(response){
        option = "";

        $.each(response.data,function(index,callback){
          // console.log('callback:' + callback)  
          // console.log('index:' + index)
          option += '<option value="' + callback.name + '">A.Y. ' + callback.name + '</option>'
        })

        $('#academicyear').html("")
        $('#academicyear').append(option)
      }
    })

    $("#start").val(moment('{{ Carbon\Carbon::now() }}').format('MMMM DD, YYYY'));
    $("#end").val(moment('{{ Carbon\Carbon::now()->addMonths(4) }}').format('MMMM DD, YYYY'));

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
