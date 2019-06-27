@extends('app.content')

@section('heading')
    {{ $osmPlace->name }}
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'admin.imports.places.osm.show', $osmPlace) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('admin.imports.places.osm.show', $osmPlace) !!}
@endsection

@section('app.content')

    <div class="row">
        <div class="col">
            <dl>
                <dt>@lang('placeshub.id')</dt>
                <dd><a href="http://data.ordnancesurvey.co.uk/doc/{{ $osmPlace->id }}"
                       target="_blank">{{ $osmPlace->id }}</a></dd>
                <dt>@lang('placeshub.osm_type')</dt>
                <dd>{{ $osmPlace->osm_type }}</dd>
            </dl>
        </div>
        <div class="col">
            @include('app._map', ['place' => $osmPlace])
        </div>
    </div>

@endsection