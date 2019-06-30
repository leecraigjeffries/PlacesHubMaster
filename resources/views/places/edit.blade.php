@extends('places.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'places.show', $place) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('places.show', $place) !!}
@endsection

@section('heading')
    {{ $place->name }}
    <button class="@if($place->approved_at) text-success @else text-danger @endif" id="approve">
        @if($place->approved_at)
            <i class="far fa-check-circle"></i> <span>@lang('moderate.approved')</span>
        @else
            <i class="far fa-times-circle"></i> <span>@lang('moderate.pending')</span>
        @endif
    </button>
@endsection

@section('places.content')

    @include('places._moderate-menu')

    @include('places._next-previous')

    <div class="row">
        <div class="col-lg">

            <form action="{{ route('places.update', $place) }}" method="POST">
                @method('PATCH')
                @csrf

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.id')
                    </div>
                    <div class="col-md-8 pt-2">
                        <a href="{!! route('places.show', $place) !!}">{{ $place->id }}</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.name')
                    </div>
                    <div class="col-md-8 pt-2">
                        <input name="name" type="text" class="form-control form-control-sm" id="name"
                               required="required" value="{{ $place->name }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.official_name')
                    </div>
                    <div class="col-md-8 pt-2">
                        <input name="official_name" type="text" class="form-control form-control-sm" id="official-name" value="{{ $place->official_name }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.type')
                    </div>
                    <div class="col-md-8 pt-2">
                        <span class="type">@lang("places.{$place->type}")</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.wikipedia_title')
                    </div>
                    <div class="col-md-8 pt-2">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                            <span class="input-group-text prepend">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="wikipedia_title_null"
                                           name="wikipedia_title_null" @if(!$place->wikipedia_title) checked="checked"
                                           @endif value="1">
                                    <label class="custom-control-label"
                                           for="wikipedia_title_null">@lang('moderate.set_to_null')</label>
                                </div>
                            </span>
                            </div>
                            <input name="wikipedia_title" type="text" class="form-control form-control-sm" id="wikipedia_title" value="{{ $place->wikipedia_title }}">
                            @if($place->wikipedia_title)
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://en.wikipedia.org/wiki/{{ $place->wikipedia_title }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.wikidata_id')
                    </div>
                    <div class="col-md-8 pt-2">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                                <span
                                    class="input-group-text prepend">{!! Form::customCheckbox('wikidata_id_null', __('moderate.set_to_null'), 1, !$place->wikidata_id) !!}</span>
                            </div>
                            {!! Form::text('wikidata_id', $place->wikidata_id, ['id' => 'wikidata_id', 'class' => 'form-control form-control-sm']) !!}
                            @if($place->wikidata_id)
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://www.wikidata.org/wiki/{{ $place->wikidata_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                            @endif
                        </div>
                        @if($place->wikipedia_wikidata_id || $place->osm_node_wikidata_id  || $place->osm_way_wikidata_id || $place->osm_relation_wikidata_id)
                            <ul class="suggestions suggestions-wikidata-id">
                                @if($place->wikipedia_wikidata_id && $place->wikipedia_wikidata_id !== $place->wikidata_id)
                                    <li>
                                        @lang('places.wikipedia_abbr'): {{ $place->wikipedia_wikidata_id}}
                                        <span class="use" data-wikidata_id="{{ $place->wikipedia_wikidata_id}}"><i
                                                class="fas fa-upload"></i></span>
                                        <a href="https://www.wikidata.org/wiki/{{ $place->wikipedia_wikidata_id}}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                    </li>
                                @endif
                                @if($place->osm_node_wikidata_id && $place->osm_node_wikidata_id !== $place->wikidata_id)
                                    <li>
                                        @lang('places.osm_abbr'): {{ $place->osm_node_wikidata_id}}
                                        <span class="use" data-wikidata_id="{{ $place->osm_node_wikidata_id}}"><i
                                                class="fas fa-upload"></i></span>
                                        <a href="https://www.wikidata.org/wiki/{{ $place->osm_node_wikidata_id}}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                    </li>
                                @endif
                                @if($place->osm_way_wikidata_id && $place->osm_way_wikidata_id !== $place->wikidata_id)
                                    <li>
                                        @lang('places.osm_abbr'): {{ $place->osm_way_wikidata_id}}
                                        <span class="use" data-wikidata_id="{{ $place->osm_way_wikidata_id}}"><i
                                                class="fas fa-upload"></i></span>
                                        <a href="https://www.wikidata.org/wiki/{{ $place->osm_way_wikidata_id}}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                    </li>
                                @endif
                                @if($place->osm_relation_wikidata_id && $place->osm_relation_wikidata_id !== $place->wikidata_id)
                                    <li>
                                        @lang('places.osm_abbr'): {{ $place->osm_relation_wikidata_id}}
                                        <span class="use" data-wikidata_id="{{ $place->osm_relation_wikidata_id}}"><i
                                                class="fas fa-upload"></i></span>
                                        <a href="https://www.wikidata.org/wiki/{{ $place->osm_relation_wikidata_id}}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>

                @foreach(['geonames_id', 'geonames_id_2', 'geonames_id_3', 'geonames_id_4'] as $property)
                    <div class="row">
                        <div class="col-md-4 font-weight-bold pt-2">
                            @lang("places.{$property}")
                        </div>
                        <div class="col-md-8 pt-2">
                            <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend">
                                    <span
                                        class="input-group-text prepend">{!! Form::customCheckbox("{$property}_null", __('moderate.set_to_null'), 1, !$place->$property) !!}</span>
                                </div>
                                {!! Form::text($property, $place->$property, ['id' => $property, 'class' => 'form-control form-control-sm']) !!}
                                @if($place->$property)
                                    <div class="input-group-append">
                                    <span class="input-group-text">
                                            <a href="https://www.geonames.org/{{ $place->$property }}"
                                               target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                    </span>
                                    </div>
                                @endif
                            </div>
                            @if($place->wikidata_geonames_id
                             && $place->wikidata_geonames_id !== $place->geonames_id
                             && $place->wikidata_geonames_id !== $place->geonames_id_2
                             && $place->wikidata_geonames_id !== $place->geonames_id_3
                             && $place->wikidata_geonames_id !== $place->geonames_id_4)
                                <ul class="suggestions suggestions-{{ $property }}">
                                    <li>
                                        @lang('places.wikidata'): {{ $place->wikidata_geonames_id }}
                                        <span class="use" data-geonames_id="{{ $place->wikidata_geonames_id }}"><i
                                                class="fas fa-upload"></i></span>
                                        <a href="https://www.geonames.org/{{ $place->wikidata_geonames_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.osm_id')
                    </div>
                    <div class="col-md-8 pt-2">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                                <span
                                    class="input-group-text prepend">{!! Form::customCheckbox('osm_id_null', __('moderate.set_to_null'), 1, !$place->osm_id) !!}</span>
                            </div>
                            {!! Form::text('osm_id', $place->osm_id, ['id' => 'osm_id', 'class' => 'form-control form-control-sm']) !!}
                            @if($place->osm_id)
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://www.openstreetmap.org/relation/{{ $place->osm_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://www.openstreetmap.org/node/{{ $place->osm_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://www.openstreetmap.org/way/{{ $place->osm_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                            @endif
                        </div>
                        @if($place->wikidata_osm_id && $place->wikidata_osm_id !== $place->osm_id)
                            <ul class="suggestions suggestions-osm-id">
                                <li>
                                    @lang('places.wikidata'): {{ $place->wikidata_osm_id }}
                                    <span class="use" data-osm_id="{{ $place->wikidata_osm_id }}"><i
                                            class="fas fa-upload"></i></span>
                                    <a href="https://www.openstreetmap.org/node/{{ $place->wikidata_osm_id }}"
                                       target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.os_id')
                    </div>
                    <div class="col-md-8 pt-2">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                                <span
                                    class="input-group-text prepend">{!! Form::customCheckbox('os_id_null', __('moderate.set_to_null'), 1, !$place->os_id) !!}</span>
                            </div>
                            {!! Form::text('os_id', $place->os_id, ['id' => 'os_id', 'class' => 'form-control form-control-sm']) !!}
                            @if($place->os_id)
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="http://data.ordnancesurvey.co.uk/doc/{{ $place->os_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="http://data.ordnancesurvey.co.uk/doc/{{ $place->os_id }}.json"
                                           target="_blank"
                                           class="badge badge-pill">json</a>
                                </span>
                                </div>
                            @endif
                        </div>
                        @if($place->wikidata_os_id && $place->wikidata_os_id !== $place->os_id)
                            <ul class="suggestions suggestions-os-id">
                                <li>
                                    @lang('places.wikidata'): {{ $place->wikidata_os_id }}
                                    <span class="use" data-os_id="{{ $place->wikidata_os_id }}"><i
                                            class="fas fa-upload"></i></span>
                                    <a href="http://data.ordnancesurvey.co.uk/doc/{{ $place->wikidata_os_id }}"
                                       target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.ons_id')
                    </div>
                    <div class="col-md-8 pt-2">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                                <span
                                    class="input-group-text prepend">{!! Form::customCheckbox('ons_id_null', __('moderate.set_to_null'), 1, !$place->ons_id) !!}</span>
                            </div>
                            {!! Form::text('ons_id', $place->ons_id, ['id' => 'ons_id', 'class' => 'form-control form-control-sm']) !!}
                            @if($place->ons_id)
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="http://statistics.data.gov.uk/doc/statistical-geography/{{ $place->ons_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="http://statistics.data.gov.uk/boundaries/{{ $place->ons_id }}.json"
                                           target="_blank"
                                           class="badge badge-pill">json</a>
                                </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.ipn_id')
                    </div>
                    <div class="col-md-8 pt-2">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                                <span
                                    class="input-group-text prepend">{!! Form::customCheckbox('ipn_id_null', __('moderate.set_to_null'), 1, !$place->ipn_id) !!}</span>
                            </div>
                            {!! Form::text('ipn_id', $place->ipn_id, ['id' => 'ipn_id', 'class' => 'form-control form-control-sm']) !!}
                            @if($place->ipn_id)
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://www.wikidata.org/wiki/{{ $place->ipn_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.lat')
                    </div>
                    <div class="col-md-8 pt-2">
                        {!! Form::number('lat', null, ['class' => 'form-control form-control-sm w-50', 'max' => 90, 'min' => -90, 'step' => 0.000001, 'id' => 'lat']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-2">
                        @lang('places.lon')
                    </div>
                    <div class="col-md-8 pt-2">
                        {!! Form::number('lon', null, ['class' => 'form-control form-control-sm w-50', 'max' => 180, 'min' => -180, 'step' => 0.000001, 'id' => 'lon']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="offset-4 pl-3 pt-2">
                        <button class="btn btn-primary" type="submit">@lang('places.update')</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
        <div class="col-lg">
            @if($place->lat)
                @include('places._map')
            @endif
        </div>
    </div>

    @include('places._siblings')

@endsection

@section('javascript')
    @parent
    <script>
        $("#approve").on("click", function () {
            $.ajax({
                url: "{{ route('api.places.approve', $place) }}"
            }).done(function (data) {
                if (data.data.approved === true) {
                    $("#approve").html("<i class=\"far fa-check-circle\"></i> <span>@lang('moderate.approved')</span>")
                        .removeClass("text-danger")
                        .addClass("text-success")
                } else {
                    $("#approve").html("<i class=\"far fa-times-circle\"></i> <span>@lang('moderate.pending')</span>")
                        .removeClass("text-success")
                        .addClass("text-danger")
                }
            })
        });


        $(".suggestions-wikipedia-title li span.use").on("click", function () {
            $("#wikipedia_title").val($(this).data("wikipedia_title"))
        });
        $(".suggestions-wikidata-id li span.use").on("click", function () {
            $("#wikidata_id").val($(this).data("wikidata_id"))
        });
        $(".suggestions-geonames_id li span.use").on("click", function () {
            $("#geonames_id").val($(this).data("geonames_id"))
        });
        $(".suggestions-geonames_id_2 li span.use").on("click", function () {
            $("#geonames_id_2").val($(this).data("geonames_id"))
        });
        $(".suggestions-geonames_id_3 li span.use").on("click", function () {
            $("#geonames_id_3").val($(this).data("geonames_id"))
        });
        $(".suggestions-geonames_id_4 li span.use").on("click", function () {
            $("#geonames_id_4").val($(this).data("geonames_id"))
        });
        $(".suggestions-os-id li span.use").on("click", function () {
            $("#os_id").val($(this).data("os_id"))
        });
        $(".suggestions-osm-id li span.use").on("click", function () {
            $("#osm_id").val($(this).data("osm_id"))
        })
    </script>
@endsection