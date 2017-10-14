@extends('layouts.master-blue')
@section('title')
Update
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/bootstrap-clockpicker.min.css')) }}
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
        <legend><h3 class="text-muted">Update Schedule</h3></legend>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('schedule') }}">Schedule</a>
          </li>
          <li class="active">Update</li>
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
        {{ Form::open(array('method'=>'put','route'=>array('schedule.update',$schedule->id),'class' => 'form-horizontal')) }}
        <div class="form-group">
          <div class="col-sm-3">
          {{ Form::label('academicyear','Semester') }}
          </div>
          <div class="col-sm-9">
          {{ Form::select('academicyear',['Loading nearest Academic Year ..'],Input::old('academicyear'),[
            'id' => 'academicyear',
            'class' => 'form-control'
          ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3">
          {{ Form::label('semester','Semester') }}
          </div>
          <div class="col-sm-9">
          {{ Form::select('semester',['Loading all Semester ..'],Input::old('semester'),[
            'id' => 'semester',
            'class' => 'form-control'
          ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3">
          {{ Form::label('room','Room') }}
          </div>
          <div class="col-sm-9">
          {{ Form::select('room',['Loading all Laboratory Rooms ..'],Input::old('room'),[
            'id' => 'room',
            'class' => 'form-control'
          ]) }}
          </div>
        </div>

        <div class="form-group">
          <div class="col-md-3">
            {{ Form::label('subject','Subject') }}
            </div>
          <div class="col-sm-9">
            {{ Form::text('subject',$schedule->subject,[
              'required',
              'class'=>'form-control',
              'placeholder'=>'Subject'
            ]) }}
          </div>
        </div>
        <!-- creator name -->
        <div class="form-group">
          <div class="col-sm-3">
          {{ Form::label('faculty','Faculty-in-charge') }}
          </div>
          <div class="col-sm-9">
          {{ Form::select('faculty',[],Input::old('faculty'),[
            'id'=>'faculty',
            'class'=>'form-control'
          ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-3">
            {{ Form::label('section','Course, Year & Section') }}
            </div>
          <div class="col-sm-9">
            {{ Form::text('section',$schedule->section,[
              'class'=>'form-control',
              'placeholder'=>'Course Year-Section'
            ]) }}
          </div>
        </div>
        <div class="form-group" id="timerange">
          <div class="col-md-6">
            {{ Form::label('timestart','Time Start') }}
            {{ Form::text('timestart',Input::old('timestart'),[
              'id' => 'starttime',
              'class'=>'form-control',
              'placeholder'=>'Time Start'
            ]) }}
          </div>
          <div class="col-md-6">
            {{ Form::label('timeend','Time End') }}
            {{ Form::text('timeend',Input::old('timeend'),[
              'id' => 'endtime',
              'class'=>'form-control time',
              'placeholder'=>'Time End'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::submit('Update',[
              'class'=>'btn btn-lg btn-primary btn-block',
              'name' => 'update'
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
{{ HTML::script(asset('js/bootstrap-clockpicker.min.js')) }}
<script>
  $(document).ready(function(){
    @if( Session::has("success-message") )
        swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif
    @if( Session::has("error-message") )
        swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type : "get",
      url : "{{ route('room.index') }}",
      dataType : "json",
      success : function(response){
        options = "";
        for(ctr = 0;ctr<response.data.length;ctr++){
          options += `<option value='`+response.data[ctr].id+`'>`+response.data[ctr].name+`</option>'`;
        }

        $('#room').html("");
        $('#room').append(options);
        @if(Input::old('room'))
        $('#room').val("{{ Input::old('location') }}"); 
        @endif
      },
      error : function(response){
        $('#room').html("<option>Loading all Laboratory Rooms ...</option>")
      }
    });

    $('#starttime').clockpicker({
        placement: 'bottom',
        align: 'left',
        // autoclose: true,
        default: 'now',
            donetext: 'Select',
            twelvehour: true,
            init: function(){
              $('#starttime').val(moment('{{ $schedule->timein }}','h:m:s').format("hh:mmA"))
            },
            afterDone: function() {
              error('#time-start-error-message','*Time started must be less than time end')
            },
    });

    $('#endtime').clockpicker({
        placement: 'bottom',
        align: 'left',
        // autoclose: true,
        fromnow: 5400000,
        default: 'now',
            donetext: 'Select',
            twelvehour: true,
            init: function(){
              $('#endtime').val(moment('{{ $schedule->timeout }}','h:m:s').format("hh:mmA"))
            },
            afterDone: function() {
              error('#time-end-error-message','*Time ended must be greater than time started')
            },
    });

    function error(attr2,message){
      if($('#endtime').val()){
        if(moment($('#starttime').val(),'hh:mmA').isBefore(moment($('#endtime').val(),'hh:mmA'))){
          $('#request').show(400);
          $('#time-end-error-message').html(``)
          $('#time-start-error-message').html(``)
          $('#time-end-group').removeClass('has-error');
          $('#time-start-group').removeClass('has-error');
        }else{
          $('#request').hide(400);
          $(attr2).html(message).show(400)
          $('#time-end-group').addClass('has-error');
          $('#time-start-group').addClass('has-error');
        }
      }
    }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          type: 'get',
          url: "{{ url('faculty') }}",
          dataType: 'json',
          success: function(response){
            items = "";
            for(ctr = 0;ctr<response.data.length;ctr++){
              lastname = response.data[ctr].lastname;
              firstname = response.data[ctr].firstname;
              if(response.data[ctr].middlename){
                middlename = response.data[ctr].middlename;
              }else{
                middlename = "";
              }
          name = lastname + ', ' + firstname + ' ' + middlename
              items += `<option value='`+ response.data[ctr].id +`'>
              ` + name + `
              </option>`;
            }

            if(response.length == 0){
                items += `<option>There are no available faculty</option>`
            }

            $('#faculty').html("");
            $('#faculty').append(items);
          },
          complete: function(){

            $('#faculty').selectize({
                update: true,
                sortField: {
                    field: 'text',
                    direction: 'asc'
                },
                dropdownParent: 'body'
            })

            $('#faculty').val({{ $schedule->faculty }})
          }
        });

    $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type : "get",
      url : "{{ url('semester') }}",
      data: {
        'academicyear': 'nearest'
      },
      dataType : "json",
      success : function(response){
        options = "";
        $.each(response.data,function(id,academicyear){
          options += `<option value='`+academicyear+`'>`+academicyear+`</option>'`;
        })

        $('#academicyear').html("");
        $('#academicyear').append(options);
        @if(Input::old('academicyear'))
        $('#academicyear').val("{{ Input::old('academicyear') }}");
        @else
        $('#academicyear').val("{{ $schedule->academicyear }}"); 
        @endif
      },
      error : function(response){
        $('#room').html("<option>Loading nearest Academic Year ...</option>")
      }
    });

    $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type : "get",
      url : "{{ url('semester') }}",
      data: {
        'semester': 'all'
      },
      dataType : "json",
      success : function(response){
        options = "";
        $.each(response.data,function(id,semester){
          options += `<option value='`+semester+`'>`+semester+`</option>'`;
        })

        $('#semester').html("");
        $('#semester').append(options);
        @if(Input::old('semester'))
        $('#semester').val("{{ Input::old('semester') }}");
        @else
        $('#semester').val("{{ $schedule->semester }}");
        @endif
      },
      error : function(response){
        $('#room').html("<option>Loading all Semesters ...</option>")
      }
    });
    
    $('#page-body').show();
  });
</script>
@stop
