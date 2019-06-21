<div class="row py-4">
    <div class="col">
        <h3>@lang("placeshub.{$type}_plural")</h3>
        <table id="list_{!! $type !!}" class="table table-striped table-bordered table-hover w-100">
            <thead class="thead-dark">
            <tr>
                <th>@lang('placeshub.name')</th>
                <th>@lang('placeshub.osm_type')</th>
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
                    url: "{{ route('api.admin.imports.places.osm.data-table', ['osmPlace' => $osmPlace, 'placesHubGeoType' => $type]) }}",
                    type: "GET"
                },
                columns: columns
            });
        });
    </script>
@endsection