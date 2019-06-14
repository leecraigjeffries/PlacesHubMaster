@extends('app.layout')

@section('content')

    <div id="app">

        <div id="content-wrapper">
            <div id="heading">
                @yield('heading')
            </div>

            @yield('breadcrumbs')

            <main>
                @include('app._errors')

                @yield('app.content')
            </main>
        </div>

    </div>

@endsection