@extends('admin.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'places.move-children.select-type', $place) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('places.move-children.select-type', $place) !!}
@endsection

@section('heading')
    @lang('placeshub.move_children_select_type')
@endsection

@section('admin.content')

    <form action="{{ route('places.move-children.select-parent', $place) }}" method="GET">
        <div class="row">
            <div class="col">
                <select name="type" class="custom-select">
                    <option value="all">@lang('placeshub.all') ({!! array_sum($counts) !!})</option>
                    @foreach($counts as $type => $count)
                        @if($count > 0)
                            <option value="{{ $type }}">@lang("placeshub.{$type}_plural") ({!! $count !!})</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col text-center p-3">
                <button type="submit"
                        class="btn btn-primary">@lang('placeshub.move_children')</button>
            </div>
        </div>
    </form>

@endsection
