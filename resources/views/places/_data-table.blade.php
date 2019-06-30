<div class="row py-4">
    <div class="col">

        <h3>@lang("places.{$type}_plural") @hasrole('mod') <a href="{{ route('places.create', [$place, $type]) }}">@lang('places.add_type', ['type' => $type])</a> @endhasrole</h3>

        <table id="list_{!! $type !!}" class="table table-striped table-bordered table-hover w-100">
            <thead class="thead-dark">
            <tr>
                <th>@lang('placeshub.name')</th>
                <th>@lang('placeshub.approved')</th>
                <th>@lang('placeshub.wikipedia_abbr')</th>
                <th>@lang('placeshub.wikidata')</th>
                <th>@lang('placeshub.geonames_abbr')</th>
                <th>@lang('placeshub.os_abbr')</th>
                <th>@lang('placeshub.ons_abbr')</th>
                <th>@lang('placeshub.osm_abbr')</th>
                <th>@lang('placeshub.coords')</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

@section('javascript')
    @parent
    <script>
        $(document).ready(function () {
            $('#list_{!! $type !!}').DataTable({
                ajax: {
                    url: "{!! route('api.places.data-table.index', ['place' => $place, 'type' => $type]) !!}",
                    type: "GET"
                },
                columns: columns
            });
        });
    </script>
@endsection