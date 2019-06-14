@extends('app.content')

@section('heading')
    Geonames Data
@endsection

@section('app.content')

    <form method="GET" action="{{ route('import.geo-places.index') }}">
        <fieldset id="filter">
            <legend>Filter</legend>
            <div class="row">
                @foreach(['name', 'adm1_name', 'adm2_name', 'adm3_name', 'adm4_name', 'adm5_name'] as $field)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-2">
                        <label for="{{ $field }}">@lang("placeshub.{$field}")</label>
                        <input name="{{ $field }}"
                               id="{{ $field }}"
                               class="form-control form-control-sm"
                               placeholder="Search"
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
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                </div>
            </div>
        </fieldset>
    </form>

    {{ $results->links() }}

    <table class="table table-striped" id="table-geo-places">
        <col class="name">
        <col class="type">
        <col class="type">
        <thead>
        <tr>
            <th scope="col">
                Name
            </th>
            <th scope="col">
                Type
            </th>
            <th scope="col">
                Code
            </th>
            <th scope="col">
                Full Code
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            <tr>
                <td>
                    <h6>{{ $result->name }}</h6>
                    <ul>
                        @foreach($result->getAdminTypes() as $type)
                            @if($result->$type)
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

    {{ $results->links() }}

@endsection