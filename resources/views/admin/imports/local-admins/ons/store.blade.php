@extends('admin.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'admin.imports.local-admins.ons.store') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('admin.imports.local-admins.ons.store') !!}
@endsection

@section('heading')
    @lang('placeshub.import_ons_places')
@endsection

@section('admin.content')

    <div class="row">
        <div class="col">
            <div class="alert @if($importSuccess) alert-success @else alert-danger @endif">
                @if($importSuccess)
                    <h5>@lang('placeshub.import_success') <i class="far fa-check-circle"></i></h5>
                    @lang('placeshub.rows_added', ['rows' => $rowCount])
                @else
                    <h5>@lang('placeshub.import_failure') <i class="far fa-check-times"></i></h5>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            @if($importSuccess)
                <a href="#TODO">@lang('placeshub.click_to_view_data')</a>
            @else
                <a href="{{ route('admin.imports.places.ons.create') }}">@lang('placeshub.click_to_return')</a>
            @endif
        </div>
    </div>

@endsection