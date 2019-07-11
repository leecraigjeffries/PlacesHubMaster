@extends('admin.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'admin.extractor-import.osni.edit') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('admin.extractor-import.osni.edit') !!}
@endsection

@section('heading')
    @lang('placeshub.extractor_import_osni')
@endsection

@section('css')
    @if(app()->environment('production'))
        @parent
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endif
@endsection

@section('admin.content')

    <form name="update" method="POST" action="{{ route('admin.extractor-import.osni.update') }}">
        @method('PATCH')
        @csrf

        <div class="text-center p-3">
            <button type="submit" class="btn btn-primary">@lang('placeshub.import')</button>
        </div>
    </form>
@endsection

@section('javascript')
    @parent
    @if(app()->environment('production'))
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @else
        <script src="{{ asset('js/vendor/flatpickr.js') }}"></script>
    @endif

    <script>
        flatpickr("#date", {
            enableTime: true,
            dateFormat: "Y-m-dTH:i",
            defaultDate: "{!! \Carbon\Carbon::yesterday()->format('Y-m-dTH:i') !!}",
            time_24hr: true
        });
    </script>
@endsection