@extends('app.content')

@section('heading')
    @lang('placeshub.geo_data')
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'import.places.geo.index') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('import.places.geo.index') !!}
@endsection

@section('app.content')

    <form method="GET" action="{{ route('import.geo-places.index') }}">
        <fieldset id="filter">
            <legend>Filter</legend>
            <div class="row">
                @foreach(['name', 'adm1_name', 'adm2_name', 'adm3_name', 'adm4_name'] as $field)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                        <label for="{{ $field }}">@lang("placeshub.{$field}")</label>
                        <input name="{{ $field }}"
                               id="{{ $field }}"
                               class="form-control form-control-sm"
                               placeholder="Search&hellip;"
                               value="{{ $request->input($field) ?: old($field) }}">
                    </div>
                @endforeach
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="type">@lang('placeshub.type')</label>
                    <select class="custom-select custom-select-sm" name="type" id="type">
                        <option value="">All</option>
                        @foreach($types as $key => $val)
                            <option
                                value="{{ $key }}" {{ (($request->input('type') ?: old('type')) === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="order_by">@lang('placeshub.order_by')</label>
                    <select class="custom-select custom-select-sm" name="order_by" id="order_by">
                        @foreach($geoSearch->getOrderByTranslated() as $key => $val)
                            <option
                                value="{{ $key }}" {{ (($geoSearch->getOrderBy() ?: old('order_by') ?: $geoSearch->getDefaultOrderBy()) === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                </div>
            </div>
        </fieldset>
    </form>

    {{ $results->appends($geoSearch->getAppends())->links() }}

    <table class="table table-striped" id="table-geo-places">
        @foreach($geoSearch->getHeadingsTranslated() as $order_by => $headingText)
            <colgroup>
                <col data-name="{{ $order_by }}">
            </colgroup>
        @endforeach
        <thead>
        <tr>
            @foreach($geoSearch->getHeadingsTranslated() as $order_by => $headingText)
                <th scope="col" @if($order_by === $geoSearch->getOrderBy()) class="active" @endif>
                    <a href="{{ route('import.geo-places.index', $geoSearch->getAppends([
                    'order_by' => $order_by,
                    'order' => $geoSearch->getOrderOpposite()
                    ])) }}">{{ $headingText }}
                        <i class="fas fa-sort ml-2"></i></a>
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            <tr>
                <td>
                    <h6>{{ $result->name }}</h6>
                    <ul>
                        @foreach($result->getAdminTypes() as $type)
                            @if($result->$type && $result->id !== $result->$type->id)
                                <li>
                                    {{ $result->$type->name }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </td>
                <td>
                    <a href="{{ route('import.geo-places.index', ['type' => $result->type]) }}">{{ $result->type }}</a>
                </td>
                <td>
                    {{ $result->geo_code }}
                </td>
                <td>
                    {{ $result->geo_code_full }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $results->appends($geoSearch->getAppends())->links() }}

@endsection