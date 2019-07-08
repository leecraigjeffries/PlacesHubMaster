<div class="row">
    <div class="col pb-3">
        @foreach($place->childTypes() as $type)
            <a class="btn btn-primary mr-2 btn-sm"
               href="{{ route('places.create', [$place, $type]) }}">@lang('placeshub.add_type', ['type' => __("placeshub.{$type}")])</a>
        @endforeach
    </div>
    <div class="dropdown float-right mr-3">
        <button class="btn btn-secondary btn-sm dropdown-toggle"
                type="button"
                id="moderate-menu"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
            @lang('placeshub.moderate')
        </button>
        <div class="dropdown-menu" aria-labelledby="moderate-menu">
            <a class="dropdown-item"
               href="{{ route('places.move.select-type', $place) }}">@lang('placeshub.move')
            </a>
            <a class="dropdown-item"
               href="{{ route('places.move-children.select-type', $place) }}">@lang('placeshub.move_children')
            </a>
            <a class="dropdown-item"
               href="{{ route('places.type.edit', $place) }}">@lang('placeshub.change_type')
            </a>

            <div class="dropdown-divider"></div>

            @if(!isset($edit))
                <a class="dropdown-item text-warning"
                   href="{{ route('places.edit', $place) }}"><i class="fas fa-edit mr-2"></i>@lang('placeshub.edit')
                </a>
            @endif

            <button type="submit"
                    class="dropdown-item d-block w-100 text-danger"
                    data-toggle="modal"
                    data-target="#delete-modal"><i class="fas fa-trash-alt mr-2"></i>@lang('placeshub.delete')
            </button>
        </div>
    </div>
</div>

<div class="modal fade"
     id="delete-modal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="delete-title"
     aria-hidden="true">
    <form method="POST" action="{{ route('places.destroy', $place) }}" name="delete-place">
        @method('DELETE')
        @csrf
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete-title">@lang('placeshub.delete')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="delete-reason-type">@lang('placeshub.delete_reason_type')</label>
                        <select class="custom-select custom-select-sm"
                                name="delete_reason_type"
                                id="delete-reason-type"
                                required>
                            @foreach(__('placeshub.delete_reasons') as $key => $reason)
                                <option
                                    value="{{ $key }}" {{ old('delete_reason_type') === $key ? 'selected' : '' }}>{{ $reason }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="delete-reason">@lang('placeshub.delete_reason')</label>
                        <textarea name="delete_reason" id="delete-reason" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        @lang('placeshub.close')
                    </button>
                    <button type="submit" name="type" value="soft" class="btn btn-danger">
                        <i class="fas fa-trash-alt mr-2"></i>@lang('placeshub.soft_delete')
                    </button>
                    <button type="submit" name="type" value="force" class="btn btn-danger">
                        <i class="fas fa-dumpster-fire mr-2"></i>@lang('placeshub.trash')
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>