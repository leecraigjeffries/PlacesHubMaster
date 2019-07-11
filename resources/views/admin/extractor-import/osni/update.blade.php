@extends('admin.layout')

@section('title')

@endsection

@section('heading')
    @lang('moderate.extractor_import_osni_update')
@endsection

@section('admin.content')
    @if($success)
        <div class="alert alert-success">
            @lang('moderate.import_success')
        </div>
    @else
        <div class="alert alert-danger">
            @lang('moderate.import_failure')
        </div>
    @endif

    <div class="text-center">
        <a class="btn btn-primary" href="{{ route('admin.extractor-import.osni.edit') }}">@lang('places.return')</a>
    </div>
@endsection