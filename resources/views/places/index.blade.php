@extends('app.content')

@section('heading')
    @lang('placeshub.geo_data')
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'places.index') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('places.index') !!}
@endsection

@section('app.content')

    <form method="GET" action="{{ route('places.index') }}">
        <fieldset id="filter">
            <legend>Filter</legend>
            <div class="row">
                @foreach(['name'] as $field)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                        <label for="{{ $field }}">@lang("placeshub.{$field}")</label>
                        <input name="{{ $field }}"
                               id="{{ $field }}"
                               class="form-control form-control-sm"
                               placeholder="@lang('placeshub.search')&hellip;"
                               value="{{ old($field) ?: $request->input($field) }}">
                    </div>
                @endforeach
                @foreach([] as $field)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                        <label for="{{ $field }}">@lang("placeshub.{$field}") <span class="case-sensitive">(Case Sensitive [A-Z0-9-])</span></label>
                        <input name="{{ $field }}"
                               id="{{ $field }}"
                               class="form-control form-control-sm"
                               placeholder="Search&hellip;"
                               value="{{ old($field) ?: $request->input($field) }}"
                               pattern="[A-Z0-9-]*">
                    </div>
                @endforeach
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="type">@lang('placeshub.type')</label>
                    <select class="custom-select custom-select-sm" name="type" id="type">
                        <option value="">All</option>
                        @foreach($types as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('type') ?: $request->input('type')) === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                        <label for="order_by">@lang('placeshub.order_by')</label>
                        <select class="custom-select custom-select-sm" name="order_by" id="order_by">
                            @foreach($placeSearch->getOrderByTranslated() as $key => $val)
                                <option
                                    value="{{ $key }}" {{ ((old('order_by') ?: $placeSearch->getOrderBy() ?: $placeSearch->getDefaultOrderBy()) === $key ? 'selected' : '') }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                        <label for="order">@lang('placeshub.order')</label>
                        <select class="custom-select custom-select-sm" name="order" id="order">
                            @foreach($placeSearch->getOrderTranslated() as $key => $val)
                                <option
                                    value="{{ $key }}" {{ ((old('order') ?: $placeSearch->getOrder() ?: $placeSearch->getDefaultOrder()) === $key ? 'selected' : '') }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-sm btn-primary mr-3">Filter</button>
                    <a href="{{ route('places.index') }}">Clear</a>
                </div>
            </div>
        </fieldset>
    </form>

    {{ $results->appends($placeSearch->getAppends())->fragment('table-places')->links() }}

    <table class="table table-striped" id="table-places">
        @foreach($placeSearch->getHeadingsTranslated() as $order_by => $headingText)
            <colgroup>
                <col data-name="{{ $order_by }}">
            </colgroup>
        @endforeach
        <thead>
        <tr>
            @foreach($placeSearch->getHeadingsTranslated() as $order_by => $headingText)
                <th scope="col" @if($order_by === $placeSearch->getOrderBy()) class="active" @endif>
                    <a href="{{ route('places.index', $placeSearch->getAppends([
                    'order_by' => $order_by,
                    'order' => $placeSearch->getOrderOpposite()
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
                    <a href="{{ route('places.show', $result) }}">{{ $result->name }}</a>
                    <ul>
                        @foreach($result->parentTypes() as $type)
                            @if($result->$type && $result->id !== $result->$type->id)
                                <li>
                                    <a href="{{ route('places.show', $result->$type) }}">{{ $result->$type->name }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </td>
                <td>
                    <a href="{{ route('places.index', ['type' => $result->type]) }}">{{ $result->type }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $results->appends($placeSearch->getAppends())->fragment('table-places')->links() }}

@endsection