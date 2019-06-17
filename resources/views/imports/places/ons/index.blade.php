@extends('app.content')

@section('heading')
    @lang('placeshub.ons_data')
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'imports.places.ons.index') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('imports.places.ons.index') !!}
@endsection

@section('app.content')

    <form method="GET" action="{{ route('imports.places.ons.index') }}">
        <fieldset id="filter">
            <legend>Filter</legend>
            <div class="row">
                @foreach(['name', 'county_name', 'district_name'] as $field)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                        <label for="{{ $field }}">@lang("placeshub.{$field}")</label>
                        <input name="{{ $field }}"
                               id="{{ $field }}"
                               class="form-control form-control-sm"
                               placeholder="Search&hellip;"
                               value="{{ old($field) ?: $onsSearch->getInput($field) }}">
                    </div>
                @endforeach
                <div class="col-sm-6 col-md-4 col-lg-3 p-2 @error('ipn_id') has-error @enderror">
                    <label for="ipn_id">@lang('placeshub.ipn_id')</label>
                    <input name="ipn_id"
                           id="ipn_id"
                           class="form-control form-control-sm"
                           placeholder="Search&hellip;"
                           value="{{ old('ipn_id') ?: $onsSearch->getInput('ipn_id') }}"
                           max="10"
                           pattern="^(IPN)?[0-9]*$">
                </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2 @error('ons_id') has-error @enderror">
                        <label for="ons_id">@lang('placeshub.ons_id')</label>
                        <input name="ons_id"
                               id="ons_id"
                               class="form-control form-control-sm"
                               placeholder="Search&hellip;"
                               value="{{ old('ons_id') ?: $onsSearch->getInput('ons_id') }}"
                               max="10"
                               pattern="^(E|W|S)?[0-9]*$">
                    </div>
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="ons_type">@lang('placeshub.ons_type')</label>
                    <select class="custom-select custom-select-sm" name="ons_type" id="ons_type">
                        <option value="">All</option>
                        @foreach($types as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('ons_type') ?: $request->input('ons_type'))  === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="order_by">@lang('placeshub.order_by')</label>
                    <select class="custom-select custom-select-sm" name="order_by" id="order_by">
                        @foreach($onsSearch->getOrderByTranslated() as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('order_by') ?: $onsSearch->getOrderBy() ?: $onsSearch->getDefaultOrderBy()) === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="order">@lang('placeshub.order')</label>
                    <select class="custom-select custom-select-sm" name="order" id="order">
                        @foreach($onsSearch->getOrderTranslated() as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('order') ?: $onsSearch->getOrder() ?: $onsSearch->getDefaultOrder()) === $key ? 'selected' : '') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-sm btn-primary mr-3">Filter</button>
                    <a href="{{ route('imports.places.ons.index') }}">Clear</a>
                </div>
            </div>
        </fieldset>
    </form>

    {{ $results->appends($onsSearch->getAppends())->fragment('table-ons-places')->links() }}

    <table class="table table-striped" id="table-ons-places">
        @foreach($onsSearch->getHeadingsTranslated() as $order_by => $headingText)
            <colgroup>
                <col data-name="{{ $order_by }}">
            </colgroup>
        @endforeach
        <thead>
        <tr>
            @foreach($onsSearch->getHeadingsTranslated() as $order_by => $headingText)
                <th scope="col" @if($order_by === $onsSearch->getOrderBy()) class="active" @endif>
                    <a href="{{ route('imports.places.ons.index', $onsSearch->getAppends([
                    'order_by' => $order_by,
                    'order' => $onsSearch->getOrderOpposite()
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
                    <a href="{{ route('imports.places.ons.show', $result) }}">{{ $result->name }}</a>
                    <ul>
                        @foreach($result->getAdminTypes() as $type)
                            @if($result->$type && $result->id !== $result->$type->id)
                                <li>
                                    <a href="{{ route('imports.places.ons.show', $result->$type) }}">{{ $result->$type->name }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </td>
                <td>
                    <a href="">{{ $result->ipn_id }}</a>
                </td>
                <td>
                    <a href="">{{ $result->ons_id }}</a>
                </td>
                <td>
                    <a href="{{ route('imports.places.ons.index', ['ons_type' => $result->ons_type]) }}">{{ $result->ons_type }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $results->appends($onsSearch->getAppends())->fragment('table-ons-places')->links() }}

@endsection