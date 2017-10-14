@extends('layouts.master-blue')
@section('title')
Create
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
  .weekDays-selector input {
    display: none!important;
  }

  .weekDays-selector input[type=checkbox] + label {
    display: inline-block;
    border-radius: 6px;
    background: #dddddd;
    height: 40px;
    width: 30px;
    margin-right: 3px;
    line-height: 40px;
    text-align: center;
    cursor: pointer;
  }

  .weekDays-selector input[type=checkbox]:checked + label {
    background: #2AD705;
    color: #ffffff;
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
          <div class="col-sm-3">
          {{ Form::label('academicyear','Academic Year') }}
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
            {{ Form::text('subject',Input::old('subject'),[
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
            {{ Form::text('section',Input::old('section'),[
              'class'=>'form-control',
              'placeholder'=>'Course Year-Section'
            ]) }}
          </div>
        </div>
        <h4 class="line-either-side">Days of Week</h4>
        <div class="weekDays-selector">
          <input type="checkbox" name="day[]" value="Monday" id="weekday-mon" class="weekday" />
          <label for="weekday-mon">M</label>
          <input type="checkbox" name="day[]" value="Tuesday" id="weekday-tue" class="weekday" />
          <label for="weekday-tue">T</label>
          <input type="checkbox" name="day[]" value="Wednesday" id="weekday-wed" class="weekday" />
          <label for="weekday-wed">W</label>
          <input type="checkbox" name="day[]" value="Thursday" id="weekday-thu" class="weekday" />
          <label for="weekday-thu">T</label>
          <input type="checkbox" name="day[]" value="Friday" id="weekday-fri" class="weekday" />
          <label for="weekday-fri">F</label>
          <input type="checkbox" name="day[]" value="Saturday" id="weekday-sat" class="weekday" />
          <label for="weekday-sat">S</label>
          <input type="checkbox" name="day[]" value="Sunday" id="weekday-sun" class="weekday" />
          <label for="weekday-sun">S</label>
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
              $('#starttime').val(moment().format("hh:mmA"))
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
              $('#endtime').val(moment().add("5400000").format("hh:mmA"))
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
                create: true,
                sortField: {
                    field: 'text',
                    direction: 'asc'
                },
                dropdownParent: 'body'
            })

            $('#faculty').val({{ Input::old('faculty') }})
          }
        });

    $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type : "get",
      url : "{{ url('academicyear') }}",
      data: {
        'academicyear': 'nearest'
      },
      dataType : "json",
      success : function(response){
        options = "";
        $.each(response.data,function(id,callback){
          options += `<option value='`+callback.name+`'>`+callback.name+`</option>'`;
        })

        $('#academicyear').html("");
        $('#academicyear').append(options);
        @if(Input::old('academicyear'))
        $('#academicyear').val("{{ Input::old('academicyear') }}"); 
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
