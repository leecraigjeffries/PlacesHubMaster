@extends('app.content')

@section('heading')
    @lang('placeshub.places_index')
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
                <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                    <label for="type">@lang('placeshub.type')</label>
                    <select class="custom-select custom-select-sm" name="type" id="type">
                        <option value="">All</option>
                        @foreach($types as $key => $val)
                            <option
                                value="{{ $key }}" {{ ((old('type') ?: $request->input('type')) === $key ? 'selected' : '') }}>@lang("placeshub.{$val}")</option>
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
                    @if($result->official_name)<em>
                        <small>({{ $result->official_name }})</small>
                    </em>@endif
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
                    <a href="{{ route('places.index', ['type' => $result->type]) }}">@lang("placeshub.{$result->type}")</a>
                </td>
                <td>
                    @if($result->wiki_title)
                        <a href="https://en.wikipedia.org/wiki/{{ $result->wiki_title }}"
                           class="badge badge-success"
                           target="_blank">@lang('placeshub.wiki_title')</a>
                    @endif
                </td>
                <td>
                    @if($result->wikidata_id)
                        <a href="https://www.wikidata.org/wiki/{{ $result->wikidata_id }}"
                           class="badge badge-success"
                           target="_blank">@lang('placeshub.wikidata')</a>
                    @endif
                </td>
                <td>
                    @if($result->osm_id)
                        <a href="https://www.openstreetmap.org/{{ $result->osm_network_type }}/{{ $result->osm_id }}"
                           class="badge badge-success"
                           target="_blank">@lang('placeshub.osm_id')</a>
                    @endif
                </td>
                <td>
                    @if($result->os_id)
                        <a href="http://data.ordnancesurvey.co.uk/doc/{{ $result->os_id }}"
                           class="badge badge-success"
                           target="_blank">@lang('placeshub.os_id')</a>
                    @endif
                </td>
                <td>
                    @if($result->ons_id)
                        <a href="http://statistics.data.gov.uk/doc/statistical-geography/{{ $result->ons_id }}"
                           class="badge badge-success"
                           target="_blank">@lang('placeshub.ons_id')</a>
                    @endif
                </td>
                <td>
                    @if($result->geo_id)
                        <a href="https://www.geonames.org/{{ $result->geo_id }}"
                           class="badge badge-success"
                           target="_blank">@lang('placeshub.geo_id')</a>
                    @endif
                    @if($result->geo_id_2)
                        <a href="https://www.geonames.org/{{ $result->geo_id_2 }}"
                           class="badge badge-success"
                           target="_blank">@lang('placeshub.geo_id_2')</a>
                    @endif
                    @if($result->geo_id_3)
                        <a href="https://www.geonames.org/{{ $result->geo_id_3 }}"
                           class="badge badge-success"
                           target="_blank">@lang('placeshub.geo_id_3')</a>
                    @endif
                    @if($result->geo_id_4)
                        <a href="https://www.geonames.org/{{ $result->geo_id_4 }}"
                           class="badge badge-success"
                           target="_blank">@lang('placeshub.geo_id_4')</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $results->appends($placeSearch->getAppends())->fragment('table-places')->links() }}

@endsection