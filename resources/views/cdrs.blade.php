@extends('layouts.app')

@section('content')
<div class="container">
  <form>
    <div class="form-row mb-3">
      <div class="col">
        <label for="disposition" class="text-capitalize">@lang('app.disposition')</label>
        <select class="form-control text-capitalize" id="disposition" name="disposition">
          @foreach (__('app.dispositions') as $key => $value)
            <option value="{{ $key }}" @if($key == (request()->disposition)) selected @endif>{{ $value }}</option>
          @endforeach
        </select>
      </div>
      <div class="col">
        <label for="month" class="text-capitalize">@lang('app.month')</label>
        <select class="form-control text-capitalize" id="month" name="month">
          @foreach (__('app.months') as $key => $value)
            <option value="{{ $key }}" @if($key == (request()->month)) selected @endif>{{ $value }}</option>
          @endforeach
        </select>
      </div>
      <div class="col">
        <label for="year" class="text-capitalize">@lang('app.year')</label>
        <select class="form-control text-capitalize" id="year" name="year">
          <option value="0">{{ __('app.all') }}</option>
          @while (true)
            <option value="{{ $min_year }}" @if($min_year == (request()->year)) selected @endif>{{ $min_year }}</option>
            @if ($min_year == date('Y'))
              @break
            @endif
            {{ $min_year++ }}
          @endwhile
        </select>
      </div>
      <div class="col">
        <label for="src" class="text-capitalize">@lang('app.src')</label>
        <select class="form-control text-capitalize" id="src" name="src">
          @foreach ($ext as $key => $value)
            <option value="{{ $key }}" @if($key == (request()->src)) selected @endif>{{ $value }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-1">
        <button type="submit" class="btn btn-primary mt-4 text-capitalize">@lang('app.filter')</button>
      </div>
    </div>
  </form>
  <table id="cdrs" class="table table-striped table-bordered" style="width:100%">
    <thead>
      <tr>
        <th class="text-capitalize">@lang('app.src')</th>
        <th class="text-capitalize">@lang('app.dst')</th>
        <th class="text-capitalize">@lang('app.disposition')</th>
        <th class="text-capitalize">@lang('app.calldate')</th>
        <th class="text-capitalize">@lang('app.duration')</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th class="text-capitalize">@lang('app.src')</th>
        <th class="text-capitalize">@lang('app.dst')</th>
        <th class="text-capitalize">@lang('app.disposition')</th>
        <th class="text-capitalize">@lang('app.calldate')</th>
        <th class="text-capitalize">@lang('app.duration')</th>
      </tr>
    </tfoot>
  </table>
</div>
@endsection
