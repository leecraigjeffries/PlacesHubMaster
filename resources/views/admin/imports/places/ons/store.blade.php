@extends('admin.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'admin.import.ons-places.store') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('admin.import.ons-places.store') !!}
@endsection

@section('heading')
    @lang('admin.import_ons_places')
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
                <a href="#TODO">@lang('admin.click_to_view_data')</a>
            @else
                <a href="{{ route('admin.import.ons-places.create') }}">@lang('admin.click_to_return')</a>
            @endif
        </div>
    </div>

@endsection