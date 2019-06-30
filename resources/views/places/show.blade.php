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
            <i class="far fa-check-circle"></i> <span>@lang('moderate.approved')</span>
        @else
            <i class="far fa-times-circle"></i> <span>@lang('moderate.pending')</span>
        @endif
    </span>
@endsection

@section('places.content')

    @include('places._moderate-menu')

    @include('places._next-previous')

    <div class="row">
        <div class="col-lg">
            <div id="property-table">
                <div>
                    <div>
                        @lang('places.id')
                    </div>
                    <div>
                        <a href="{!! route('places.show', $place) !!}">{{ $place->id }}</a>
                    </div>
                </div>

                @if($place->official_name)
                    <div>
                        <div>
                            @lang('places.official_name')
                        </div>
                        <div>
                            {{ $place->official_name }}
                        </div>
                    </div>
                @endif

                <div>
                    <div>
                        @lang('places.type')
                    </div>
                    <div>
                        <span class="type">@lang("places.{$place->type}")</span>
                        @if($place->type_2)
                            <span class="type">@lang("places.{$place->type_2}")</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg">
            @if($place->lat)
                @include('places._map')
            @endif
        </div>
    </div>

    @include('places._siblings')

    @foreach($place->getChildTypes() as $child_type)
        @include('places._data-table', ['type' => $child_type])
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
                data: 'wikipedia_title',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    string = "";
                    if (oData.wikipedia_title) {
                        string = "<a href=\"https://en.wikipedia.org/wiki/" + oData.wikipedia_title + "\" target=\"_blank\">" + oData.wikipedia_title + "</a>";
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
                data: 'geonames_id',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    string = "";
                    if (oData.geonames_id) {
                        string = "<a href=\"https://www.geonames.org/" + oData.geonames_id + "\" target=\"_blank\"><span class=\"badge badge-success\">GEO</span>"
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