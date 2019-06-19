@extends('app.content')

@section('heading')
    {{ $osPlace->name }}
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'imports.places.os.show', $osPlace) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('imports.places.os.show', $osPlace) !!}
@endsection

@section('app.content')

    <div class="row">
        <div class="col">
            <dl>
                <dt>@lang('placeshub.id')</dt>
                <dd><a href="https://www.osnames.org/{{ $osPlace->id }}" target="_blank">{{ $osPlace->id }}</a></dd>
                <dt>@lang('placeshub.os_type')</dt>
                <dd>{{ $osPlace->os_type }}</dd>
                @if($osPlace->os_code)
                    <dt>@lang('placeshub.os_code')</dt>
                    <dd>{{ $osPlace->os_code }}</dd>
                @endif
                @if($osPlace->os_code_full)
                    <dt>@lang('placeshub.os_code_full')</dt>
                    <dd>{{ $osPlace->os_code_full }}</dd>
                @endif
            </dl>
        </div>
        <div class="col">
            @include('app._map', ['place' => $osPlace])
        </div>
    </div>

    @foreach($osPlace->childTypes() as $childType)
        @include('imports.places.os._data-table', ['type' => $childType, 'osPlace' => $osPlace])
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
                data: 'os_type',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.os_type);
                }
            },
            {
                data: 'os_code',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.os_code);
                }
            },
            {
                data: 'os_code_full',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.os_code_full);
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