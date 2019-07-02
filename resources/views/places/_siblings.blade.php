<div class="row">
    <div class="col mb-3">
        <h3 class="mt-3 mb-0">@lang('placeshub.siblings')</h3>
        <div class="siblings">
            @if(count($siblings))
                <ul class="p-0">
                    @foreach($siblings as $sibling)
                        <li>
                            <a href="{{ route('places.show', ['place' => $sibling]) }}">{{ $sibling->name }}</a>
                            @if($sibling->lat and $place->lat)
                                <span class="sibling-distance">{!! distance_between_coordinates($sibling->lat, $sibling->lon, $place->lat, $place->lon, 'km', 0) !!} km</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <em>@lang('places.none')</em>
            @endif
        </div>
    </div>
</div>