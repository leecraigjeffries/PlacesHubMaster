@extends('app.content')

@section('heading')
    @lang('placeshub.geo_data')
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'imports.places.geo.index') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('imports.places.geo.index') !!}
@endsection

@section('app.content')

    <form method="GET" action="{{ route('imports.places.geo.index') }}">
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
                @foreach(['geo_code', 'geo_code_full'] as $field)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                        <label for="{{ $field }}">@lang("placeshub.{$field}") <span class="case-sensitive">(Case Sensitive [A-Z0-9-])</span></label>
                        <input name="{{ $field }}"
                               id="{{ $field }}"
                               class="form-control form-control-sm"
                               placeholder="Search&hellip;"
                               value="{{ $request->input($field) ?: old($field) }}"
                               pattern="[A-Z0-9-]*">
                    </div>
                @endforeach
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="geo_type">@lang('placeshub.geo_type')</label>
                    <select class="custom-select custom-select-sm" name="geo_type" id="geo_type">
                        <option value="">All</option>
                        @foreach($types as $key => $val)
                            <option
                                value="{{ $key }}" {{ (($request->input('geo_type') ?: old('geo_type')) === $key ? 'selected' : '') }}>{{ $val }}</option>
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
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                        <label for="order">@lang('placeshub.order')</label>
                        <select class="custom-select custom-select-sm" name="order" id="order">
                            @foreach($geoSearch->getOrderTranslated() as $key => $val)
                                <option
                                    value="{{ $key }}" {{ (($geoSearch->getOrder() ?: old('order') ?: $geoSearch->getDefaultOrder()) === $key ? 'selected' : '') }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-sm btn-primary mr-3">Filter</button>
                    <a href="{{ route('imports.places.geo.index') }}">Clear</a>
                </div>
            </div>
        </fieldset>
    </form>

    {{ $results->appends($geoSearch->getAppends())->fragment('table-geo-places')->links() }}

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
                    <a href="{{ route('imports.places.geo.index', $geoSearch->getAppends([
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
                    <a href="{{ route('imports.places.geo.show', $result) }}">{{ $result->name }}</a>
                    <ul>
                        @foreach($result->getAdminTypes() as $type)
                            @if($result->$type && $result->id !== $result->$type->id)
                                <li>
                                    <a href="{{ route('imports.places.geo.show', $result->$type) }}">{{ $result->$type->name }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </td>
                <td>
                    <a href="{{ route('imports.places.geo.index', ['geo_type' => $result->geo_type]) }}">{{ $result->geo_type }}</a>
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

    {{ $results->appends($geoSearch->getAppends())->fragment('table-geo-places')->links() }}

@endsection