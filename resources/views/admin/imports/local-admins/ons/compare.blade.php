@extends('admin.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'admin.imports.local-admins.ons.compare') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('admin.imports.local-admins.ons.compare') !!}
@endsection

@section('heading')
    @lang('placeshub.compare_ons_local_admins')
@endsection

@section('admin.content')

    <div class="row">
        <div class="col">
            <h6>In OnsLocalAdmin not in Place <span class="badge badge-info">{!! count($onsExtra) !!}</span></h6>
            <ul>
                @foreach($onsExtra as $onsExtraItem)
                    <li>
                        <a target="_blank"
                           href="http://statistics.data.gov.uk/doc/statistical-geography/{!! $onsExtraItem->id !!}">{!! $onsExtraItem->id !!}</a>
                        - {{ $onsExtraItem->name }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col">
            <h6>In Place not in OnsLocalAdmin <span class="badge badge-info">{!! count($placeExtra) !!}</span></h6>
            <ul>
                @foreach($placeExtra as $placeExtraItem)
                    <li>
                        <a target="_blank"
                           href="{{ route('places.show', $placeExtraItem) }}">{!! $placeExtraItem->ons_id !!}</a>
                        - {{ $placeExtraItem->name }}
                        <a href="http://statistics.data.gov.uk/doc/statistical-geography/{!! $placeExtraItem->ons_id !!}"
                           target="_blank"><i class="fas fa-external-link-alt"></i></a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection