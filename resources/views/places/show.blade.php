@extends('places.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'places.show', $place) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('places.show', $place) !!}
@endsection

@section('heading')
    {{ $place->name }}
    <span class="@if($place->approved_at) text-success @else text-danger @endif">
        @if($place->approved_at)
            <i class="far fa-check-circle"></i> <span>@lang('placeshub.approved')</span>
        @else
            <i class="far fa-times-circle"></i> <span>@lang('placeshub.pending')</span>
        @endif
    </span>
@endsection

@section('places.content')

    @include('places._moderate-menu')

    @include('places._next-previous')

    <div class="row">
        <div class="col-lg">
            <dl>
                <dt>
                    @lang('placeshub.id')
                </dt>
                <dd>
                    <a href="{!! route('places.show', $place) !!}">{{ $place->id }}</a>
                </dd>

                @if($place->official_name)
                    <dt>
                        @lang('placeshub.official_name')
                    </dt>
                    <dd>
                        {{ $place->official_name }}
                    </dd>
                @endif

                <dt>
                    @lang('placeshub.type')
                </dt>
                <dd>
                    <span class="type">@lang("placeshub.{$place->type}")</span>
                    @if($place->type_2)
                        <span class="type">@lang("placeshub.{$place->type_2}")</span>
                    @endif
                </dd>

                @if($place->wiki_title)
                    <dt>
                        @lang('placeshub.wiki_title')
                    </dt>
                    <dd>
                        <a href="https://en.wikipedia.org/wiki/{{ $place->wiki_title }}"
                           target="_blank">{{ $place->wiki_title }}</a>
                    </dd>
                @endif

                @if($place->wikidata_id)
                    <dt>
                        @lang('placeshub.wikidata_id')
                    </dt>
                    <dd>
                        <a href="https://www.wikidata.org/wiki/{{ $place->wikidata_id }}"
                           target="_blank">{{ $place->wikidata_id }}</a>
                    </dd>
                @endif

                @if($place->osm_id)
                    <dt>
                        @lang('placeshub.osm_id')
                    </dt>
                    <dd>
                        <a href="https://www.openstreetmap.org/{{ $place->osm_network_type }}/{{ $place->osm_id }}"
                           target="_blank">{{ $place->osm_id }}</a>
                        ({{ $place->osm_network_type }})
                    </dd>
                @endif

                @if($place->os_id)
                    <dt>
                        @lang('placeshub.os_id')
                    </dt>
                    <dd>
                        <a href="http://data.ordnancesurvey.co.uk/doc/{{ $place->os_id }}"
                           target="_blank">{{ $place->os_id }}</a>
                    </dd>
                @endif

                @if($place->ons_id)
                    <dt>
                        @lang('placeshub.ons_id')
                    </dt>
                    <dd>
                        <a href="http://statistics.data.gov.uk/doc/statistical-geography/{{ $place->ons_id }}"
                           target="_blank">{{ $place->ons_id }}</a>
                    </dd>
                @endif

                @if($place->ipn_id)
                    <dt>
                        @lang('placeshub.ipn_id')
                    </dt>
                    <dd>
                        {{ $place->ipn_id }}
                    </dd>
                @endif

                @foreach(['geo_id', 'geo_id_2', 'geo_id_3', 'geo_id_4'] as $col)
                    @if($place->$col)
                        <dt>
                            @lang("placeshub.{$col}")
                        </dt>
                        <dd>
                            <a href="https://www.geonames.org/{{ $place->$col }}" target="_blank">{{ $place->$col }}</a>
                        </dd>
                    @endif
                @endforeach

                @if($place->point)
                    <dt>
                        @lang('placeshub.point')
                    </dt>
                    <dd>
                        {{ $place->point->toWKT() }}
                    </dd>
                @endif

                @if($place->iso3166_2)
                    <dt>
                        @lang('placeshub.iso3166_2')
                    </dt>
                    <dd>
                        <a href="https://www.iso.org/obp/ui/#iso:code:3166:GB"
                           target="_blank">{{ $place->iso3166_2 }}</a>
                    </dd>
                @endif
            </dl>
        </div>
        <div class="col-lg">
            @if($place->lat)
                @include('places._map')
            @endif
        </div>
    </div>

    @include('places._siblings')

    @foreach($place->getChildTypes() as $childType)
        @include('places._data-table', ['type' => $childType])
    @endforeach

@endsection

@section('javascript')
    @parent
    @if(app()->environment('production'))
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
    @else
        <script src="{{ asset('js/vendor/data-tables.js') }}"></script>
        <script src="{{ asset('js/vendor/data-tables-bs4.js') }}"></script>
    @endif

    <script>
        var columns = [
            {
                data: 'name',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html("<a href=\"" + oData.link + "\">" + oData.name + "</a>");
                }
            },
            {
                data: 'approved_at',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    if (oData.approved_at) {
                        string = "<i class=\"far fa-check-circle text-success\"></i>";
                    } else {
                        string = "<i class=\"far fa-times-circle text-danger\"></i>"
                    }
                    $(nTd).html(string);
                }
            },
            {
                data: 'wiki_title',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    string = "";
                    if (oData.wiki_title) {
                        string = "<a href=\"https://en.wikipedia.org/wiki/" + oData.wiki_title + "\" target=\"_blank\">" + oData.wiki_title + "</a>";
                    }
                    $(nTd).html(string);
                }
            },
            {
                data: 'wikidata_id',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    string = "";
                    if (oData.wikidata_id) {
                        string = "<a href=\"https://www.wikidata.org/wiki/" + oData.wikidata_id + "\" target=\"_blank\"><span class=\"badge badge-success\">Wikidata</span></a>";
                    }
                    $(nTd).html(string);
                }
            },
            {
                data: 'geo_id',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    string = "";
                    if (oData.geo_id) {
                        string = "<a href=\"https://www.geonames.org/" + oData.geo_id + "\" target=\"_blank\"><span class=\"badge badge-success\">GEO</span>"
                    }
                    $(nTd).html(string);
                }
            },
            {
                data: 'os_id',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    string = "";
                    if (oData.os_id) {
                        string = "<a href=\"http://data.ordnancesurvey.co.uk/doc/" + oData.os_id + "\" target=\"_blank\"><span class=\"badge badge-success\">OS</span>"
                    }
                    $(nTd).html(string);
                }
            },
            {
                data: 'ons_id',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    string = "";
                    if (oData.ons_id) {
                        string += "<a href=\"http://statistics.data.gov.uk/doc/statistical-geography/" + oData.ons_id + "\" target=\"_blank\"><span class=\"badge badge-success\">ONS</span>";
                    }
                    if (oData.ipn_id) {
                        string += "<span class=\"badge badge-success\">IPN</span>";
                    }
                    $(nTd).html(string);
                }
            },
            {
                data: 'osm_id',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    string = "";
                    if (oData.osm_id) {
                        string = "<a href=\"https://www.openstreetmap.org/relation/" + oData.osm_id + "\" target=\"_blank\"><span class=\"badge badge-success\">OSM</span>";
                    }
                    $(nTd).html(string);
                }
            },
            {
                data: 'lat',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    if (oData.lat != null && oData.lon != null) {
                        $(nTd).html("<a href=\"https://www.openstreetmap.org/?mlat=" + oData.lat + "&amp;mlon=" + oData.lon + "#map=5/" + oData.lat + "/" + oData.lon + "\" target=\"_blank\" title=\"" + oData.lat + ", " + oData.lon + "\"><i class=\"fas fa-globe-americas\"></i></a>");
                    }
                }
            }
        ];
    </script>
@endsection