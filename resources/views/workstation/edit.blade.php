@extends('layouts.master-blue')
@section('title')
Workstation | Update
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/jquery-ui.min.css')) }}
{{ HTML::style(asset('css/animate.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.min.css') }}" />
<style>
  {
    display:none;
  }
  #page-body,#page-two,#page-three{
    display:none;
  }

  .form-control{
    margin: 10px 0px;
  }
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
  <div class="panel panel-default col-md-offset-3 col-md-6" style="padding:10px;">
    <div class="panel-body">
      <legend><h3 class="text-primary">Workstation</h3></legend>
      <ul class="breadcrumb">
        <li><a href="{{ url('workstation') }}">Workstation</a></li>
        <li class="active">Assemble</li>
      </ul>
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
      {{ Form::open(['method'=>'put','route'=>array('workstation.update',$pc->id)]) }}
          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('os','Operating System Key') }}
              {{ Form::text('os',$pc->oskey,[
                'id' => 'os',
                'class'=>'form-control',
                'placeholder'=>'Operating System Key',
                'required'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('systemunit','System Unit Property Number') }}
              <p>
                <span class="text-muted">
                  @if($pc->systemunit)
                  {{ $pc->systemunit->propertynumber }}
                  @else
                  None
                  @endif
                </span>
              </p>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('monitor','Monitor Property Number') }}
              <p>
                <span class="text-danger">Old Value:</span>
                <span class="text-muted">
                  @if($pc->monitor)
                  {{ $pc->monitor->propertynumber }}
                  @else
                  None
                  @endif
                </span>
              </p>
              {{ Form::text('monitor',Input::old('monitor'),[
                'id'=>'monitor',
                'class'=>'form-control',
                'placeholder' => 'Monitor'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('avr','AVR Property Number') }}
              <p>
                <span class="text-danger">Old Value:</span>
                <span class="text-muted">
                  @if($pc->avr)
                  {{ $pc->avr->propertynumber }}
                  @else
                  None
                  @endif
                </span>
              </p>
              {{ Form::text('avr',Input::old('avr'),[
                'id'=>'avr',
                'class'=>'form-control',
                'placeholder' => 'AVR'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('keyboard','Keyboard Property Number') }}
              <p>
                <span class="text-danger">Old Value:</span>
                <span class="text-muted">
                  @if($pc->keyboard)
                  {{ $pc->keyboard->propertynumber }}
                  @else
                  None
                  @endif
                </span>
              </p>
              {{ Form::text('keyboard',Input::old('keyboard'),[
                'id'=>'keyboard',
                'class'=>'form-control',
                'placeholder' => 'Keyboard'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('mouse','Mouse Brand') }}
              <p>
                <span class="text-danger">Old Value:</span>
                <span class="text-muted">
                  @if($pc->mouse)
                  {{ $pc->mouse }}
                  @else
                  None
                  @endif
                </span>
              </p>
            </div>
            <div class="col-sm-12">
              <input type="checkbox" value="true" id="toggle-mouse" name="mousetag" /> Replace mouse?
              {{ Form::text('mouse',Input::old('mouse'),[
                'id'=>'mouse',
                'class'=>'form-control',
                'placeholder' => 'Mouse Brand',
                'style' => 'display:none;'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              <button class="btn btn-primary btn-lg btn-block btn-flat" name="create" type="submit"><span class="glyphicon glyphicon-check"></span> Update </button>
            </div>
          </div>
        </div>
      {{ Form::close() }}
    </div>
  </div>
</div><!-- Container -->
@stop
@section('script')
{{ HTML::script(asset('js/jquery-ui.js')) }}
{{ HTML::script(asset('js/moment.min.js')) }}
<script>
  $(document).ready(function(){

    $('#keyboard').autocomplete({
      source: "{{ url('get/item/profile/keyboard/propertynumber') }}"
    });

    $('#monitor').autocomplete({
      source: "{{ url('get/item/profile/monitor/propertynumber') }}"
    });

    $('#systemunit').autocomplete({
      source: "{{ url('get/item/profile/systemunit/propertynumber') }}"

    });

    $('#avr').autocomplete({
      source: "{{ url('get/item/profile/avr/propertynumber') }}"

    });

    $('#mouse').autocomplete({
      source: "{{ url('get/supply/mouse/brand') }}"
    });

    @if( Session::has("success-message") )
        swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif
    @if( Session::has("error-message") )
        swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    $('#toggle-mouse').change(function()
    {
      if($('#toggle-mouse').is(':checked'))
      {
        $('#mouse').show()
      }
      else
      {
        $('#mouse').hide()
      }
    })

    $('#page-body').show();

  });
</script>
@stop
