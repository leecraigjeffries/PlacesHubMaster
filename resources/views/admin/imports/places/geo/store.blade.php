@extends('admin.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'admin.imports.places.geo.store') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('admin.imports.places.geo.store') !!}
@endsection

@section('heading')
    @lang('admin.import_geo_places')
@endsection

@section('admin.content')

    <div class="row">
        <div class="col">
            <div class="alert @if($importSuccess) alert-success @else alert-danger @endif">
                @if($importSuccess)
                    <h5>@lang('admin.import_success') <i class="far fa-check-circle"></i></h5>
                    @lang('admin.rows_added', ['rows' => $rowCount])
                @else
                    <h5>@lang('admin.import_failure') <i class="far fa-check-times"></i></h5>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            @if($importSuccess)
                <a href="{{ route('imports.places.geo.index') }}">@lang('admin.click_to_view_data')</a>
            @else
                <a href="{{ route('admin.imports.places.geo.create') }}">@lang('admin.click_to_return')</a>
            @endif
        </div>
    </div>

@endsection