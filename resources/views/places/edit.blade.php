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

    <div class="row mb-2">
        <div class="col">
            <a href="{{ route('places.show', $place) }}">@lang('placeshub.back')</a>
        </div>
    </div>

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
                               value="{{ old('name') ?? $place->name }}"
                               required>
                    </div>
                </div>

                <div class="row">
                    <label for="official-name" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.official_name')
                    </label>
                    <div class="col-md-8">
                        <input name="official_name"
                               type="text"
                               class="form-control form-control-sm"
                               id="official-name"
                               max="191"
                               value="{{ old('official_name') ?? $place->official_name }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.type')
                    </div>
                    <div class="col-md-8">
                        <span class="badge badge-sm badge-success">@lang("placeshub.{$place->type}")</span>
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
                            <input name="wiki_title"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="wiki-title"
                                   max="191"
                                   value="{{ old('wiki_title') ?? $place->wiki_title }}">

                            @if($place->wiki_title)
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://en.wikipedia.org/wiki/{{ $place->wiki_title }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                            @endif
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
                            <input name="wikidata_id"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="wikidata-id"
                                   max="191"
                                   value="{{ old('wikidata_id') ?? $place->wikidata_id }}">

                            @if($place->wikidata_id)
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://www.wikidata.org/wiki/{{ $place->wikidata_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                            @endif
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
                            <input name="osm_id"
                                   type="number"
                                   class="form-control form-control-sm"
                                   id="osm-id"
                                   value="{{ old('osm_id') ?? $place->osm_id }}">

                            @if($place->osm_id)
                                <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://www.openstreetmap.org/{{ $place->osm_network_type }}/{{ $place->osm_id }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="osm-network-type" class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.osm_network_type')
                        <a href="{{ route('admin.imports.places.osm.index', ['name' => $place->name]) }}"
                           target="_blank"><i class="fas fa-search ml-2"></i></a>
                    </label>
                    <div class="col-md-8">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                            <span class="input-group-text prepend">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="osm-network-type-null"
                                           name="osm_network_type_null"
                                           @if(!$place->osm_network_type) checked="checked" @endif
                                           value="1">
                                    <label class="custom-control-label"
                                           for="osm-network-type-null">@lang('placeshub.set_to_null')</label>
                                </div>
                            </span>
                            </div>
                            <!-- todo: change to select -->
                            <input name="osm_network_type"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="osm-network-type"
                                   max="9"
                                   pattern="^(node|relation|way)$"
                                   value="{{ old('osm_network_type') ?? $place->osm_network_type }}">
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
                            <input name="os_id"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="os-id"
                                   max="191"
                                   value="{{ old('os_id') ?? $place->os_id }}">

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
                                           target="_blank">json</a>
                                </span>
                                </div>
                            @endif
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
                            <input name="ons_id"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="ons-id"
                                   max="9"
                                   pattern="^(E|W|S)?[0-9]{8}$"
                                   value="{{ old('ons_id') ?? $place->ons_id }}">

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
                                           target="_blank">json</a>
                                </span>
                                </div>
                            @endif
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
                            <input name="ipn_id"
                                   type="text"
                                   class="form-control form-control-sm"
                                   id="ipn-id"
                                   max="10"
                                   pattern="^(IPN)?[0-9]*$"
                                   value="{{ old('ipn_id') ?? $place->ipn_id }}">
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

                                <input name="{{ $col }}"
                                       type="number"
                                       class="form-control form-control-sm"
                                       id="{{ str_replace('_', '-', $col) }}"
                                       value="{{ old($col) ?? $place->$col }}">

                                @if($place->$col)
                                    <div class="input-group-append">
                                <span class="input-group-text">
                                        <a href="https://www.geonames.org/{{ $place->$col }}"
                                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                    </div>
                                @endif
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
                               value="{!! old('lat') ?? $place->lat !!}">
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
                               value="{!! old('lon') ?? $place->lon !!}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.point')
                    </div>
                    <div class="col-md-8">
                        @if($place->point)
                            <i class="far fa-check-circle text-success"></i>
                        @else
                            <i class="far fa-times-circle text-danger"></i>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.polygon')
                    </div>
                    <div class="col-md-8">
                        @if($place->polygon)
                            <i class="far fa-check-circle text-success"></i>
                        @else
                            <i class="far fa-times-circle text-danger"></i>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 font-weight-bold pt-1">
                        @lang('placeshub.multipolygon')
                    </div>
                    <div class="col-md-8">
                        @if($place->multipolygon)
                            <i class="far fa-check-circle text-success"></i>
                        @else
                            <i class="far fa-times-circle text-danger"></i>
                        @endif
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

    @foreach($place->childTypes() as $childType)
        @include('places._data-table', ['type' => $childType])
    @endforeach

@endsection

@section('javascript')
    @parent

    @include('places._data-table-columns')

    <script>
        $("#approve").on("click", function () {
            $.ajax({
                url: "{{ route('api.places.approve', $place) }}"
            }).done(function (data) {
                if (data.data.approved === true) {
                    $("#approve").html("<i class=\"far fa-check-circle\"></i> <span>@lang('placeshub.approved')</span>")
                        .removeClass("text-danger")
                        .addClass("text-success")
                } else {
                    $("#approve").html("<i class=\"far fa-times-circle\"></i> <span>@lang('placeshub.pending')</span>")
                        .removeClass("text-success")
                        .addClass("text-danger")
                }
            })
        });

        // TODO: osm network type to select box
        ['wiki_title', 'wikidata_id', 'osm_id', 'osm_network_type', 'os_id', 'ons_id', 'ipn_id', 'geo_id', 'geo_id_2', 'geo_id_3', 'geo_id_4'].forEach(function (inputName) {
            let inputBox = $("input[name='" + inputName + "']");
            inputBox.on("focus input keyup", function () {
                if (inputBox.val().length > 0) {
                    $("input[name='" + inputName + "_null']").prop("checked", false);
                } else {
                    $("input[name='" + inputName + "_null']").prop("checked", true);
                }
            })
        })
    </script>
@endsection