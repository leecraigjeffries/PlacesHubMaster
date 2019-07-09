@extends('admin.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'places.move-children.select-parent', $place, $type) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('places.move-children.select-parent', $place, $type) !!}
@endsection

@section('heading')
    @lang('placeshub.move_children')
@endsection

@section('admin.content')

    <div class="row">
        <div class="col">
            <h3>@lang('placeshub.search')</h3>
            <input type="text"
                   class="form-control"
                   placeholder="@lang('placeshub.search')"
                   id="q"
                   name="q"
                   autocomplete="off">
        </div>
        <div class="col">
            <h3>Results</h3>
            <div id="parent-results"></div>
        </div>
    </div>

@endsection

@section('javascript')
    @parent
    <script id="move-search-template" type="text/x-jsrender">
<div class="result">
   <form action="@{{:uri}}" method="POST">
   @csrf
        @method('PATCH')
        <span>
        <button type="submit" class="btn btn-secondary btn-sm">@{{:name}}</a></button> <em>(@{{:type}})</em>
        </span>
        </form>
        @{{if parents.length}}
            <ul>
                @{{for parents}}
                    <li>
                        <a href="@{{>uri}}">@{{>name}}</a>
                    </li>
                @{{/for}}
            </ul>
        @{{/if}}
     </div>
    </script>
    <script type="text/javascript">
        $("#q").on("keyup input focus", $.debounce(400, function () {
            if ($("#q").val().length > 0) {
                $.get({
                    url: "{{ route('api.places.move-children.search', ['place' => $place, 'type' => $type ?? 'all']) }}",
                    data: {
                        q: $(this).val()
                    }
                }).done(function (data) {
                    if (data.data.length > 0) {
                        $("#parent-results").html($.templates("#move-search-template").render(data.data))
                    } else {
                        $("#parent-results").html("No results...")
                    }
                }).fail(function (data) {
                    $("#parent-results").html("No results...")

                })
            } else {
                $("#parent-results").html("No results...")
            }
        }))
    </script>
@endsection