@extends('app.content')

@section('heading')
    {{ $geoPlace->name }}
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'admin.imports.places.geo.show', $geoPlace) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('admin.imports.places.geo.show', $geoPlace) !!}
@endsection

@section('app.content')

    <div class="row">
        <div class="col">
            <dl>
                <dt>@lang('placeshub.id')</dt>
                <dd><a href="https://www.geonames.org/{{ $geoPlace->id }}" target="_blank">{{ $geoPlace->id }}</a></dd>
                <dt>@lang('placeshub.geo_type')</dt>
                <dd>{{ $geoPlace->geo_type }}</dd>
                @if($geoPlace->geo_code)
                    <dt>@lang('placeshub.geo_code')</dt>
                    <dd>{{ $geoPlace->geo_code }}</dd>
                @endif
                @if($geoPlace->geo_code_full)
                    <dt>@lang('placeshub.geo_code_full')</dt>
                    <dd>{{ $geoPlace->geo_code_full }}</dd>
                @endif
            </dl>
        </div>
        <div class="col">
            @include('app._map', ['place' => $geoPlace])
        </div>
    </div>

    @foreach($geoPlace->childTypes() as $childType)
        @include('admin.imports.places.geo._data-table', ['type' => $childType, 'geoPlace' => $geoPlace])
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
        let columns = [
            {
                data: 'name',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html("<a href=\"" + oData.link + "\">" + oData.name + "</a>");
                }
            },
            {
                data: 'geo_type',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.geo_type);
                }
            },
            {
                data: 'geo_code',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.geo_code);
                }
            },
            {
                data: 'geo_code_full',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.geo_code_full);
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