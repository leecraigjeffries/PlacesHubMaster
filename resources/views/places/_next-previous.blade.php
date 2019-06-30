@if($previous || $next)
    <div class="row mb-3">
        @if($previous)
            <div class="col">
                &laquo; <a href="{{ route(isset($edit) ? 'places.edit' : 'places.show', $previous) }}">{{ $previous->name }}</a>
            </div>
        @endif
        @if($next)
            <div class="col text-right">
                <a href="{{ route(isset($edit) ? 'places.edit' : 'places.show', $next) }}">{{ $next->name }}</a> &raquo;
            </div>
        @endif
    </div>
@endif