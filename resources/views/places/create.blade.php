@extends('admin.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'places.create', $place, $type) !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('places.create', $place, $type) !!}
@endsection

@section('heading')
    @lang('placeshub.create_type', ['type' => __("placeshub.{$type}")])
@endsection

@section('admin.content')

    <a href="{{ route('places.show', $place) }}">Back</a>

    <h2 class="mt-3">@lang('placeshub.wikipedia_extractor')</h2>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('api.extractor.wikipedia-titles') }}" name="wikipedia_query" method="GET">
                <div class="row">
                    <label for="title" class="font-weight-bold col-5 col-form-label">@lang('placeshub.title')</label>
                    <div class="col">
                        <input type="text" name="title" id="title" class="form-control"
                               placeholder="e.g. United States topic">
                        <small id="emailHelp"
                               class="form-text text-muted">@lang('placeshub.cannot_contain_wiki')</small>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col offset-5">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="type1" name="type" class="custom-control-input" checked="checked"
                                   value="template">
                            <label class="custom-control-label" for="type1">@lang('placeshub.template')</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="type2" name="type" class="custom-control-input" value="category">
                            <label class="custom-control-label" for="type2">@lang('placeshub.category')</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row mt-3">
                    <label for="wanted_keys"
                           class="font-weight-bold col-5 col-form-label">@lang('placeshub.wanted_keys')</label>
                    <div class="col">
                        <input type="text" name="wanted_keys" id="wanted_keys" class="form-control"
                               placeholder="e.g. list1">
                        <small id="emailHelp" class="form-text text-muted">@lang('placeshub.divided_by_pipe')</small>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col offset-5">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="star_split" name="star_split"
                                   value="true">
                            <label for="star_split" class="custom-control-label">@lang('placeshub.star_split')</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-primary">@lang('placeshub.query')</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <textarea name="pl-links" id="pl-links" class="form-control" rows="12"></textarea>
            <button class="btn btn-primary mt-3" id="pl-send-to-list"><i
                    class="fas fa-arrow-down"></i> @lang('placeshub.send_to_list')</button>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="name-only" name="name_only">
                <label class="custom-control-label" for="name-only">@lang('placeshub.name_only')</label>
            </div>
        </div>
    </div>


    <h2>Items</h2>
    <form action="{{ route('places.store', [$place, $type]) }}" name="place_create" method="POST">
        @csrf
        <div class="row">
            <div class="col text-center font-weight-bold">
                @lang('placeshub.wiki_title')
            </div>
            <div class="col text-center font-weight-bold">
                @lang('placeshub.name')
            </div>
            <div class="col">
            </div>
        </div>
        <div id="pl-rows">
            @if(old('title') || old('name'))
                @foreach(old('title') as $key => $title)
                    @include('places._create-row')
                @endforeach
            @else
                @include('places._create-row')
            @endif
        </div>
        <div class="row">
            <div class="col">
                <button class="btn btn-primary" id="pl-add-row"><i class="fas fa-plus"></i> @lang('placeshub.add_row')
                </button>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection

@section('javascript')
    @parent
    <script id="pl-add-row-template" type="text/x-jsrender">
        <div class="row mb-3 pl-add-row">
            <div class="col-5">
                <input type="text" name="title[]" placeholder="@lang('places.title')" value="@{{:title}}" class="form-control">
            </div>
            <div class="col-5">
                <input type="text" name="name[]" placeholder="@lang('places.name')" required="required" value="@{{:name}}" class="form-control">
            </div>
            <div class="col-2">
                <button class="btn btn-danger btn-sm pl-delete-row"><i class="fas fa-times"></i></button>
            </div>
        </div>
    </div>



    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsrender/0.9.91/jsrender.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"
            integrity="sha256-Qfxgn9jULeGAdbaeDjXeIhZB3Ra6NCK3dvjwAG8Y+xU="
            crossorigin="anonymous"></script>

    <script>
        function addRow(name, title) {
            $("#pl-rows").append($.templates("#pl-add-row-template").render({"name": name, "title": title}));
        }

        $(document).on("click", ".pl-delete-row", function () {
            $(this).parents(".pl-add-row").remove();
            if (!$('#pl-rows input[name="title[]"]').length) {
                addRow('', '')
            }
            if ($('#pl-rows input[name="title[]"]').length < $("#user_allowed_to_submit").val()) {
                $("#pl-add-row").show()
            }
        });
        $("#pl-add-row").on("click", function () {
            addRow('', '')
        })
        $("form[name='wikipedia_query']").on("submit", function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: "GET",
                data: {
                    "type": $("input[name='type']:checked").val(),
                    "title": $("input[name='title']").val(),
                    "wanted_keys": $("input[name='wanted_keys']").val(),
                    "star_split": $("input[name='star_split']").is(':checked') == true ? 1 : 0
                }
            }).done(function (data) {
                $("#pl-links").val(data)
            }).fail(function (jqXHR) {
                swal({
                    type: 'error',
                    text: jqXHR.responseJSON.message
                })
            });
        })
        $('#pl-send-to-list').on('click', function () {
            if ($('#pl-links').val().length > 0) {
                $('.pl-add-row').remove();

                var rows = $('#pl-links').val().split('\n');

                for (var i = 0; i < rows.length; i++) {
                    rows[i] = rows[i].trim();
                    if (rows[i]) {
                        row = rows[i].split('|');

                        if (row.length == 1) {
                            if ($("input[name='name_only']").is(':checked')) {
                                addRow(row[0], '');
                            } else {
                                addRow(row[0].trim(), row[0].trim());
                            }
                        } else if (row.length > 1) {
                            addRow(row[1].trim(), row[0].trim());
                        }
                    }
                }
            }
        })
    </script>
@endsection
