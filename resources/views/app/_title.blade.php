@if (count($breadcrumbs))
    @foreach (array_reverse($breadcrumbs->toArray()) as $breadcrumb)
        {{ $breadcrumb->title }} @if(!$loop->last) / @endif
    @endforeach
    - PlacesHub
@endif