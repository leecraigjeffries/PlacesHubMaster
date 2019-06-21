@extends('app.content')

@section('heading')
    @lang('placeshub.osm_data')
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'admin.imports.places.osm.index') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('admin.imports.places.osm.index') !!}
@endsection

@section('app.content')

    <form method="GET" action="{{ route('admin.imports.places.osm.index') }}">
        <fieldset id="filter">
            <legend>Filter</legend>
            <div class="row">
                @foreach(['name', 'macro_region_name', 'region_name', 'county_name', 'district_name'] as $field)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                        <label for="{{ $field }}">@lang("placeshub.{$field}")</label>
                        <input name="{{ $field }}"
                               id="{{ $field }}"
                               class="form-control form-control-sm"
                               placeholder="Search&hellip;"
                               value="{{ old($field) ?: $osmSearch->getInput($field) }}">
                    </div>
                @endforeach
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="osm_type">@lang('placeshub.osm_type')</label>
                    <select class="custom-select custom-select-sm" name="osm_type" id="osm_type">
                        <option value="">All</option>
                        @foreach($types as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('osm_type') ?: $request->input('osm_type'))  === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="order_by">@lang('placeshub.order_by')</label>
                    <select class="custom-select custom-select-sm" name="order_by" id="order_by">
                        @foreach($osmSearch->getOrderByTranslated() as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('order_by') ?: $osmSearch->getOrderBy() ?: $osmSearch->getDefaultOrderBy()) === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="order">@lang('placeshub.order')</label>
                    <select class="custom-select custom-select-sm" name="order" id="order">
                        @foreach($osmSearch->getOrderTranslated() as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('order') ?: $osmSearch->getOrder() ?: $osmSearch->getDefaultOrder()) === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-sm btn-primary mr-3">Filter</button>
                    <a href="{{ route('admin.imports.places.osm.index') }}">Clear</a>
                </div>
            </div>
        </fieldset>
    </form>

    {{ $results->appends($osmSearch->getAppends())->fragment('table-osm-places')->links() }}

    <table class="table table-striped" id="table-osm-places">
        @foreach($osmSearch->getHeadingsTranslated() as $order_by => $headingText)
            <colgroup>
                <col data-name="{{ $order_by }}">
            </colgroup>
        @endforeach
        <thead>
        <tr>
            @foreach($osmSearch->getHeadingsTranslated() as $order_by => $headingText)
                <th scope="col" @if($order_by === $osmSearch->getOrderBy()) class="active" @endif>
                    <a href="{{ route('admin.imports.places.osm.index', $osmSearch->getAppends([
                    'order_by' => $order_by,
                    'order' => $osmSearch->getOrderOpposmite()
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
                    <a href="{{ route('admin.imports.places.osm.show', $result) }}">{{ $result->name }}</a>
                    <ul>
                        @foreach($result->getAdminTypes() as $type)
                            @if($result->$type && $result->id !== $result->$type->id)
                                <li>
                                    <a href="{{ route('admin.imports.places.osm.show', $result->$type) }}">{{ $result->$type->name }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </td>
                <td>
                    <a href="{{ route('admin.imports.places.osm.index', ['osm_type' => $result->osm_type]) }}">{{ $result->osm_type }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $results->appends($osmSearch->getAppends())->fragment('table-osm-places')->links() }}

@endsection