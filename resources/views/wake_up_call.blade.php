@extends('layouts.app')

@section('content')
<div class="container">
  @if ($errors->any())
    <div class="alert alert-danger text-capitalize">
      <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  @if(session('success'))
    <div class="alert alert-success text-capitalize">
      <ul>
        <li>{{ session('success') }}</li>
      </ul>
    </div>
  @endif
  <form class="mb-3" method="POST" action="{{ route('wake_up_call.store') }}" id="wake-up-call">
    @csrf
    <div class="form-group">
      <label for="ext" class="text-capitalize">@lang('app.ext')</label>
      <select class="form-control text-capitalize" id="ext" name="ext">
        @foreach ($ext as $key => $value)
          @if (!in_array($loop->index, [0, 1]))
            <option value="{{ $key }}" @if(old('ext')) selected @endif>{{ $value }}</option>
          @endif
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="datetime" class="text-capitalize">@lang('app.datetime')</label>
      <div class="input-group date" id="datetimepicker" data-target-input="nearest">
        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker" id="datetime" name="datetime"/>
        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="tries" class="text-capitalize">@lang('app.tries')</label>
      <input type="text" class="form-control" id="tries" name="tries" value="{{ old('tries', 1) }}">
    </div>
    <div class="form-group">
      <label for="waittime" class="text-capitalize">@lang('app.waittime')</label>
      <select class="form-control text-capitalize" id="waittime" name="waittime">
        @foreach ($time as $value)
          <option value="{{ $value }}" @if(old('waittime')) selected @endif>{{ $value }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="retrytime" class="text-capitalize">@lang('app.retrytime')</label>
      <select class="form-control text-capitalize" id="retrytime" name="retrytime">
        @foreach ($time as $value)
          <option value="{{ $value }}" @if(old('retrytime')) selected @endif>{{ $value }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="supervisor" class="text-capitalize">@lang('app.supervisor')</label>
      <select class="form-control text-capitalize" id="supervisor" name="supervisor">
        @foreach ($ext as $key => $value)
          @if (!in_array($loop->index, [0, 1]))
            <option value="{{ $key }}" @if(old('supervisor')) selected @elseif(cache('supervisor', 2050) == $value) selected @endif>{{ $value }}</option>
          @endif
        @endforeach
      </select>
    </div>
    <button type="submit" class="btn btn-primary text-capitalize">@lang('app.schedule')</button>
    </form>
    <table id="wake-up-calls" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th class="text-capitalize">@lang('app.datetime')</th>
          <th class="text-capitalize">@lang('app.ext')</th>
          <th class="text-capitalize">@lang('app.tries')</th>
          <th class="text-capitalize">@lang('app.waittime')</th>
          <th class="text-capitalize">@lang('app.retrytime')</th>
          <th class="text-capitalize">@lang('app.supervisor')</th>
          <th></th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th class="text-capitalize">@lang('app.datetime')</th>
          <th class="text-capitalize">@lang('app.ext')</th>
          <th class="text-capitalize">@lang('app.tries')</th>
          <th class="text-capitalize">@lang('app.waittime')</th>
          <th class="text-capitalize">@lang('app.retrytime')</th>
          <th class="text-capitalize">@lang('app.supervisor')</th>
          <th></th>
        </tr>
      </tfoot>
    </table>
</div>
@endsection
