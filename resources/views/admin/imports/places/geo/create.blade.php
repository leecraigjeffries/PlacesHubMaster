@extends('admin.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'admin.imports.places.geo.create') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('admin.imports.places.geo.create') !!}
@endsection

@section('heading')
    @lang('admin.import_geo_places')
@endsection

@section('admin.content')

    <div class="row">
        <div class="col">
            <div class="alert @if($fileExists) alert-success @else alert-danger @endif">
                @if($fileExists)
                    <h5>@lang('admin.file_exists_title') <i class="far fa-check-circle"></i></h5>
                @else
                    <h5>@lang('admin.file_not_exists_title') <i class="far fa-check-times"></i></h5>
                @endif
                @lang('admin.file', ['file' => $filePath])
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">

        </div>
        <div class="col">
            <form name="store" method="POST" action="{{ route('admin.imports.places.geo.store') }}">
                @csrf
                <button type="submit" class="btn btn-primary">@lang('admin.import')</button>
            </form>
        </div>
    </div>

@endsection