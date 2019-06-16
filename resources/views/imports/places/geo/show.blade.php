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
            <dt>@lang('placeshub.geo_type')</dt>
            <dd>{{ $geoPlace->geo_type }}</dd>
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

    @foreach($geoPlace->childTypes() as $childType)
        @include('imports.places.geo._data-table', ['type' => $childType, 'place' => $geoPlace])
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
            }
        ];
    </script>
@endsection