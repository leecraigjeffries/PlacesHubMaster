@extends('admin.layout')

@section('title')
    {!! Breadcrumbs::view('app._title', 'admin.imports.local-admins.ons.create') !!}
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('admin.imports.local-admins.ons.create') !!}
@endsection

@section('heading')
    @lang('placeshub.import_ons_local_admins')
@endsection

@section('admin.content')

    <div class="row">
        <div class="col">
            <div class="alert @if($fileExists) alert-success @else alert-danger @endif">
                @if($fileExists)
                    <h5>@lang('placeshub.file_exists_title') <i class="far fa-check-circle"></i></h5>
                @else
                    <h5>@lang('placeshub.file_not_exists_title') <i class="far fa-check-times"></i></h5>
                @endif
                @lang('placeshub.file', ['file' => $filePath])
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">

        </div>
        <div class="col">
            <form name="store" method="POST" action="{{ route('admin.imports.local-admins.ons.store') }}">
                @csrf
                <button type="submit" class="btn btn-primary">@lang('placeshub.import')</button>
            </form>
        </div>
    </div>

@endsection