<div class="embed-responsive embed-responsive-4by3 border">
    <iframe class="embed-responsive-item" scrolling="no"
            src="http://www.openstreetmap.org/export/embed.html?bbox={!! $place->lon - $place->getRatio() !!}%2C{!! $place->lat - $place->getRatio() !!}%2C{!! $place->lon + $place->getRatio()  !!}%2C{!! $place->lat + $place->getRatio()  !!}&amp;marker={!! $place->lat !!}%2C{!! $place->lon !!}&amp;layer=mapnik"></iframe>
</div>
<div class="py-2 text-muted">
    <a target="_blank"
       href="https://www.openstreetmap.org/?mlat={!! $place->lat !!}&amp;mlon={!! $place->lon !!}#map=10/{!! $place->lat !!}/{!! $place->lon !!}"
       class="coord-link"><i class="fas fa-globe-americas" aria-hidden="true"></i>{!! $place->lat !!}&deg;
        {!! $place->lon !!}&deg;</a>
</div>