@extends('app.content')

@section('heading')
    @lang('placeshub.os_data')
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'imports.places.os.index') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('imports.places.os.index') !!}
@endsection

@section('app.content')

    <form method="GET" action="{{ route('imports.places.os.index') }}">
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
                               value="{{ old($field) ?: $osSearch->getInput($field) }}">
                    </div>
                @endforeach
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="os_type">@lang('placeshub.os_type')</label>
                    <select class="custom-select custom-select-sm" name="os_type" id="os_type">
                        <option value="">All</option>
                        @foreach($types as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('os_type') ?: $request->input('os_type'))  === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="order_by">@lang('placeshub.order_by')</label>
                    <select class="custom-select custom-select-sm" name="order_by" id="order_by">
                        @foreach($osSearch->getOrderByTranslated() as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('order_by') ?: $osSearch->getOrderBy() ?: $osSearch->getDefaultOrderBy()) === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="order">@lang('placeshub.order')</label>
                    <select class="custom-select custom-select-sm" name="order" id="order">
                        @foreach($osSearch->getOrderTranslated() as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('order') ?: $osSearch->getOrder() ?: $osSearch->getDefaultOrder()) === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-sm btn-primary mr-3">Filter</button>
                    <a href="{{ route('imports.places.os.index') }}">Clear</a>
                </div>
            </div>
        </fieldset>
    </form>

    {{ $results->appends($osSearch->getAppends())->fragment('table-os-places')->links() }}

    <table class="table table-striped" id="table-os-places">
        @foreach($osSearch->getHeadingsTranslated() as $order_by => $headingText)
            <colgroup>
                <col data-name="{{ $order_by }}">
            </colgroup>
        @endforeach
        <thead>
        <tr>
            @foreach($osSearch->getHeadingsTranslated() as $order_by => $headingText)
                <th scope="col" @if($order_by === $osSearch->getOrderBy()) class="active" @endif>
                    <a href="{{ route('imports.places.os.index', $osSearch->getAppends([
                    'order_by' => $order_by,
                    'order' => $osSearch->getOrderOpposite()
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
                    <a href="{{ route('imports.places.os.show', $result) }}">{{ $result->name }}</a>
                    <ul>
                        @foreach($result->getAdminTypes() as $type)
                            @if($result->$type && $result->id !== $result->$type->id)
                                <li>
                                    <a href="{{ route('imports.places.os.show', $result->$type) }}">{{ $result->$type->name }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </td>
                <td>
                    <a href="{{ route('imports.places.os.index', ['os_type' => $result->os_type]) }}">{{ $result->os_type }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $results->appends($osSearch->getAppends())->fragment('table-os-places')->links() }}

@endsection