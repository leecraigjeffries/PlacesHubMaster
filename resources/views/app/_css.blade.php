@if(app()->environment('production'))
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
@else
    <link rel="stylesheet" href="{{ asset('css/app/dev.css') }}">
@endif

<link rel="stylesheet" type="text/css" href="{!! asset('css/app/app.css') !!}">

@yield('css')