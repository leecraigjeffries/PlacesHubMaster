@extends('admin.layout')

@section('admin.content')
    <div class="row">
        <div class="col">
            Confirm dump
            <form action="{{ route('admin.dumps.places.store') }}" method="POST">
                @csrf
                <button type="submit"
                        class="btn btn-primary">@lang('placeshub.dump')</button>
            </form>
        </div>
    </div>

@endsection