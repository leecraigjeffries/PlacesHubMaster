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
                <dd><a href="http://data.ordnancesurvey.co.uk/doc/{{ $osmPlace->id }}" target="_blank">{{ $osmPlace->id }}</a></dd>
                <dt>@lang('placeshub.osm_type')</dt>
                <dd>{{ $osmPlace->osm_type }}</dd>
            </dl>
        </div>
        <div class="col">
            @include('app._map', ['place' => $osmPlace])
        </div>
    </div>

    @foreach($osmPlace->childTypes() as $childType)
        @include('admin.imports.places.osm._data-table', ['type' => $childType, 'osmPlace' => $osmPlace])
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
                data: 'osm_type',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.osm_type);
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