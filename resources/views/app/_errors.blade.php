@if(count($errors))
    <div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i class="fas fa-exclamation-triangle mr-2"></i> <strong>@lang('places.errors')</strong>
        <ol>
            @foreach ( $errors->all() AS $error )
                <li>{{ $error }}</li>
            @endforeach
        </ol>
    </div>
@endif