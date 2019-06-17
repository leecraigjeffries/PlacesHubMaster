@extends('app.content')

@section('heading')
    {{ $onsPlace->name }}
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'imports.places.ons.show', $onsPlace) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('imports.places.ons.show', $onsPlace) !!}
@endsection

@section('app.content')

    <div class="row">
        <div class="col">
            <dl>
                <dt>@lang('placeshub.id')</dt>
                <dd><a href="https://www.onsnames.org/{{ $onsPlace->id }}" target="_blank">{{ $onsPlace->id }}</a></dd>
                <dt>@lang('placeshub.ons_type')</dt>
                <dd>{{ $onsPlace->ons_type }}</dd>
                @if($onsPlace->ons_code)
                    <dt>@lang('placeshub.ons_code')</dt>
                    <dd>{{ $onsPlace->ons_code }}</dd>
                @endif
                @if($onsPlace->ons_code_full)
                    <dt>@lang('placeshub.ons_code_full')</dt>
                    <dd>{{ $onsPlace->ons_code_full }}</dd>
                @endif
            </dl>
        </div>
        <div class="col">
            @include('app._map', ['place' => $onsPlace])
        </div>
    </div>

    @foreach($onsPlace->childTypes() as $childType)
        @include('imports.places.ons._data-table', ['type' => $childType, 'onsPlace' => $onsPlace])
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
                data: 'ons_type',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.ons_type);
                }
            },
            {
                data: 'ons_code',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.ons_code);
                }
            },
            {
                data: 'ons_code_full',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.ons_code_full);
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