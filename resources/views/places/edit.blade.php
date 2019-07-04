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
            <i class="far fa-check-circle"></i> <span>@lang('placeshub.approved')</span>
        @else
            <i class="far fa-times-circle"></i> <span>@lang('placeshub.pending')</span>
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
                    <label for="name" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.name')
                    </label>
                    <div class="col-md-8">
                        <input name="name"
                               type="text"
                               class="form-control form-control-sm"
                               id="name"
                               max="191"
                               value="{{ $place->name }}"
                               required>
                    </div>
                </div>

                <div class="row">
                    <label for="official-name" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.official_name')
                    </label>
                    <div class="col-md-8">
                        <input name="name"
                               type="text"
                               class="form-control form-control-sm"
                               id="official-name"
                               max="191"
                               value="{{ $place->official_name }}">
                    </div>
                </div>

                <div class="row">
                    <label for="wiki-title" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.wiki_title')
                        <a href="https://en.wikipedia.org/w/index.php?search={{ $place->name }}"
                           target="_blank"><i class="fas fa-search ml-2"></i></a>
                    </label>
                    <div class="col-md-8">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                            <span class="input-group-text prepend">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="wiki-title-null"
                                           name="wiki_title_null"
                                           @if(!$place->wiki_title) checked="checked" @endif
                                           value="1">
                                    <label class="custom-control-label"
                                           for="wiki-title-null">@lang('placeshub.set_to_null')</label>
                                </div>
                            </span>
                            </div>
                            <input name="name"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="wiki-title"
                                   max="191"
                                   value="{{ $place->wiki_title }}">

                            <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://en.wikipedia.org/wiki/{{ $place->wiki_title }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="wikidata-id" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.wikidata_id')
                        <a href="https://www.wikidata.org/w/index.php?search={{ $place->name }}"
                           target="_blank"><i class="fas fa-search ml-2"></i></a>
                    </label>
                    <div class="col-md-8">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                            <span class="input-group-text prepend">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="wikidata-id-null"
                                           name="wikidata_id_null"
                                           @if(!$place->wikidata_id) checked="checked" @endif
                                           value="1">
                                    <label class="custom-control-label"
                                           for="wikidata-id-null">@lang('placeshub.set_to_null')</label>
                                </div>
                            </span>
                            </div>
                            <input name="name"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="wikidata-id"
                                   max="191"
                                   value="{{ $place->wikidata_id }}">

                            <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://www.wikidata.org/wiki/{{ $place->wikidata_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="osm-id" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.osm_id')
                        <a href="https://www.openstreetmap.org/search?query={{ $place->name }}"
                           target="_blank"><i class="fas fa-search ml-2"></i></a>
                    </label>
                    <div class="col-md-8">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                            <span class="input-group-text prepend">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="osm-id-null"
                                           name="osm_id_null"
                                           @if(!$place->osm_id) checked="checked" @endif
                                           value="1">
                                    <label class="custom-control-label"
                                           for="osm-id-null">@lang('placeshub.set_to_null')</label>
                                </div>
                            </span>
                            </div>
                            <input name="name"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="osm-id"
                                   max="191"
                                   value="{{ $place->osm_id }}">

                            <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://www.openstreetmap.org/{{ $place->osm_network_type }}/{{ $place->osm_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="os-id" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.os_id')
                        <a href="{{ route('admin.imports.places.os.index', ['name' => $place->name]) }}"
                           target="_blank"><i class="fas fa-search ml-2"></i></a>
                    </label>
                    <div class="col-md-8">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                            <span class="input-group-text prepend">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="os-id-null"
                                           name="os_id_null"
                                           @if(!$place->os_id) checked="checked" @endif
                                           value="1">
                                    <label class="custom-control-label"
                                           for="os-id-null">@lang('placeshub.set_to_null')</label>
                                </div>
                            </span>
                            </div>
                            <input name="name"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="os-id"
                                   max="191"
                                   value="{{ $place->os_id }}">

                            <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="http://data.ordnancesurvey.co.uk/doc/{{ $place->os_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                            </div>

                            <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="http://data.ordnancesurvey.co.uk/doc/{{ $place->os_id }}.json"
                                           target="_blank">json</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="ons-id" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.ons_id')
                        <a href="{{ route('admin.imports.places.ons.index', ['name' => $place->name]) }}"
                           target="_blank"><i class="fas fa-search ml-2"></i></a>
                    </label>
                    <div class="col-md-8">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                            <span class="input-group-text prepend">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="ons-id-null"
                                           name="ons_id_null"
                                           @if(!$place->ons_id) checked="checked" @endif
                                           value="1">
                                    <label class="custom-control-label"
                                           for="ons-id-null">@lang('placeshub.set_to_null')</label>
                                </div>
                            </span>
                            </div>
                            <input name="name"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="ons-id"
                                   max="9"
                                   pattern="^(E|W|S)?[0-9]*$"
                                   value="{{ $place->ons_id }}">

                            <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="http://data.ordnancesurvey.co.uk/doc/{{ $place->ons_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                            </div>

                            <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="http://statistics.data.gov.uk/boundaries/{{ $place->ons_id }}.json"
                                           target="_blank">json</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="ipn-id" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.ipn_id')
                    </label>
                    <div class="col-md-8">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                            <span class="input-group-text prepend">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="ipn-id-null"
                                           name="ipn_id_null"
                                           @if(!$place->ipn_id) checked="checked" @endif
                                           value="1">
                                    <label class="custom-control-label"
                                           for="ipn-id-null">@lang('placeshub.set_to_null')</label>
                                </div>
                            </span>
                            </div>
                            <input name="name"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="ipn-id"
                                   max="10"
                                   pattern="^(IPN)?[0-9]*$"
                                   value="{{ $place->ipn_id }}">
                        </div>
                    </div>
                </div>

                @foreach(['geo_id', 'geo_id_2', 'geo_id_3', 'geo_id_4'] as $col)
                    <div class="row">
                        <label for="{{ str_replace('_', '-', $col) }}" class="col-md-4 font-weight-bold pt-1">
                            @lang("placeshub.{$col}")
                            <a href="{{ route('admin.imports.places.geo.index', ['name' => $place->name]) }}"
                               target="_blank"><i class="fas fa-search ml-2"></i></a>
                        </label>
                        <div class="col-md-8">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                            <span class="input-group-text prepend">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="{{ str_replace('_', '-', $col) }}-null"
                                           name="{{ $col }}_null"
                                           @if(!$place->$col) checked="checked" @endif
                                           value="1">
                                    <label class="custom-control-label"
                                           for="{{ str_replace('_', '-', $col) }}-null">@lang('placeshub.set_to_null')</label>
                                </div>
                            </span>
                                </div>
                                <input name="name"
                                       type="text"
                                       class="form-control form-control-sm"
                                       id="{{ str_replace('_', '-', $col) }}"
                                       max="191"
                                       value="{{ $place->$col }}">
                            </div>
                        </div>
                    </div>
                @endforeach


                <div class="row">
                    <label for="lat" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.lat')
                    </label>
                    <div class="col-md-8">
                        <input name="lat"
                               type="number"
                               class="form-control form-control-sm w-50"
                               max="90"
                               min="-90"
                               step="0.000001"
                               id="lat"
                               value="{!! $place->lat !!}">
                    </div>
                </div>

                <div class="row">
                    <label for="lon" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.lon')
                    </label>
                    <div class="col-md-8">
                        <input name="lon"
                               type="number"
                               class="form-control form-control-sm w-50"
                               max="90"
                               min="-90"
                               step="0.000001"
                               id="lon"
                               value="{!! $place->lon !!}">
                    </div>
                </div>

                <div class="row">
                    <div class="col p-2 text-center">
                        <button type="submit"
                                class="btn btn-primary">@lang('placeshub.update')</button>
                    </div>
                </div>


            </form>


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
    </script>
@endsection