@extends('admin.layout')

@section('title')
    @isset($moveChildren)
        {!! Breadcrumbs::view('app._title', 'places.move-children.edit', $place) !!}
    @else
        {!! Breadcrumbs::view('app._title', 'places.move.edit', $place) !!}
    @endisset
@endsection

@section('breadcrumbs')
    @isset($moveChildren)
        {!! Breadcrumbs::render('places.move-children.edit', $place) !!}
    @else
        {!! Breadcrumbs::render('places.move.edit', $place) !!}
    @endisset
@endsection

@section('heading')
    @isset($moveChildren) @lang('placeshub.move_children') @else @lang('placeshub.move') @endisset
@endsection

@section('admin.content')

    <div class="row">
        <div class="col">
            <input type="text"
                   class="form-control"
                   placeholder="@lang('placeshub.search')"
                   id="q"
                   name="q"
                   autocomplete="off">
        </div>
        <div id="results" class="col"></div>
    </div>

@endsection

@section('javascript')
    @parent
    <script type="text/javascript">
        $("#q").on("keyup change focus", $.debounce(400, function () {
            if ($("#q").val().length > 0) {
                $.get({
                    @isset($moveChildren)
                    url: "{{ route('api.places.move-children.search', $place) }}",
                    @else
                    url: "{{ route('api.places.move.search', $place) }}",
                    @endisset
                    data: {
                        q: $(this).val()
                    }
                }).done(function (data) {
                    $("#results").html(data)
                })
            } else {
                $("#results").html("")
            }
        }))
    </script>
@endsection