@extends('app.content')

@section('heading')
    {{ $geoPlace->name }}
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'imports.places.geo.show', $geoPlace) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('imports.places.geo.show', $geoPlace) !!}
@endsection

@section('app.content')

    <div class="row">
        <div class="col">
        <dl>
            <dt>@lang('placeshub.type')</dt>
            <dd>{{ $geoPlace->type }}</dd>
        </dl>
        <dl>
            <dt>@lang('placeshub.geo_code')</dt>
            <dd>{{ $geoPlace->geo_code }}</dd>
        </dl>
        <dl>
            <dt>@lang('placeshub.geo_code_full')</dt>
            <dd>{{ $geoPlace->geo_code_full }}</dd>
        </dl>
        </div>
        <div class="col">

        </div>

    </div>


@endsection