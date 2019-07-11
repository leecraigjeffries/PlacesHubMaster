<div class="row @if($errors->has($name)) errors @endif mb-3">
    <div class="col-md">
        <label for="{!! $name !!}">{!! $text !!}
            @if($required ?? null)
                <sup class="text-danger">@lang('places.required')</sup>
            @endif
            @if($tooltip ?? null)
                <i class="fas fa-question-circle" aria-hidden="true" data-html="true" data-toggle="tooltip"
                   data-placement="top" title="{!! $tooltip !!}"></i>
            @endif
        </label>
        @if($help ?? null)
            <ul class="help">
                @foreach ($help as $help_item)
                    <li>{!! $help_item !!}</li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="col-md">
        <div class="form-group">
            <div class="input-group">
                @if($prepend ?? null)
                    <div class="input-group-append">
                        <div class="input-group-text">{!! $prepend !!}</div>
                    </div>
                @endif
                {!! $input !!}
                @if($append ?? null)
                    <div class="input-group-append">
                        <div class="input-group-text">{!! $append !!}</div>
                    </div>
                @endif
            </div>
        </div>
        @if($errors->has($name))
            <div class="invalid-feedback d-block">
                <ul class="m-0 p-0">
                    @foreach($errors->get($name) as $error)
                        <li class="list-unstyled">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>