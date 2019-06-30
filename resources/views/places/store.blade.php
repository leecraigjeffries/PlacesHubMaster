@extends('places.layout')

@section('heading')
    @lang('moderate.summary')
@endsection

@section('title')
    {!! Breadcrumbs::view('app._title', 'places.store', $place) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('places.store', $place) !!}
@endsection

@section('places.content')

    <div class="py-2">
        <a href="{!! route('places.show', ['place' => $place]) !!}" class="btn btn-primary"><i
                class="fas fa-undo"></i> @lang('places.return')</a>
    </div>

    <div class="row">
        <div class="col-md alert-success p-2 border border-success mx-2 mt-2">
            <i class="fas fa-check"></i> @lang('moderate.successes'): <span
                class="badge badge-success">{!! count($entries->successes) !!}</span>
            @if(count($entries->successes))
                <table class="table table-striped table-bordered table-hover w-100 mt-2">
                    <thead class="thead-dark">
                    <tr>
                        <th>
                            @lang('places.title')
                        </th>
                        <th>
                            @lang('places.name')
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($entries->successes as $key => $success)
                        <tr>
                            <td class="text-success">
                                @if($success->title)
                                    <a href="https://en.wikipedia.org/wiki/{{ $success->title }}" target="_blank"
                                       title="{{ $success->title }}" class="text-success">{{ $success->title }}</a>
                                @endif
                            </td>
                            <td class="text-success">
                                <a href="{{ route('places.show', $success) }}"
                                   class="text-success">{{ $success->name }}</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="col-md alert-warning p-2 border border-warning mx-2 mt-2">
            <i class="fas fa-database"></i> @lang('moderate.duplicates'): <span
                class="badge badge-warning">{!! count($entries->duplicates) !!}</span>
            @if(count($entries->duplicates))
                <table class="table table-striped table-bordered table-hover w-100 mt-2">
                    <thead class="thead-dark">
                    <tr>
                        <th>
                            @lang('places.title')
                        </th>
                        <th>
                            @lang('places.name')
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($entries->duplicates as $duplicate)
                        <tr>
                            <td class="text-warning">
                                @if($duplicate->title)
                                    <a href="https://en.wikipedia.org/wiki/{{ $duplicate->title }}" target="_blank"
                                       class="text-warning" title="{{ $duplicate->title }}">{{ $duplicate->title }}</a>
                                @endif
                            </td>
                            <td class="text-warning">
                                <a href="{{ route('places.show', $duplicate) }}"
                                   class="text-warning">{{ $duplicate->name }}</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

        </div>
        <div class="col-md alert-danger p-2 border border-danger mx-2 mt-2">
            <i class="fas fa-times"></i> @lang('moderate.fails'): <span
                class="badge badge-danger">{!! count($entries->fails) !!}</span>
            @if(count($entries->fails))
                <table class="table table-striped table-bordered table-hover w-100 mt-2">
                    <thead class="thead-dark">
                    <tr>
                        <th>
                            @lang('places.title')
                        </th>
                        <th>
                            @lang('places.name')
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($entries->fails as $key => $fail)
                        <tr>
                            <td class="text-danger">
                                {{ $fail->title }}
                            </td>
                            <td class="text-danger">
                                {{ $fail->name }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@endsection