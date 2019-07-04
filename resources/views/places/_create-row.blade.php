<div class="row mb-3 pl-add-row @if(isset($key) && ($errors->has("title.{$key}") || $errors->has("name.{$key}"))) errors  @endif">
    <div class="col-5">
        <input type="text"
               name="title[]"
               class="form-control @if(isset($key) && $errors->has("title.{$key}")) is-invalid @endif"
               placeholder="@lang('placeshub.wiki_title')"
               @isset($key) value="{{ old("title.{$key}") }}" @endisset>

    </div>
    <div class="col-5">
        <input type="text"
               name="name[]"
               class="form-control  @if(isset($key) && $errors->has("name.{$key}")) is-invalid @endif"
               placeholder="@lang('placeshub.name')"
               required
               @isset($key) value="{{ old("name.{$key}") }}" @endisset>
    </div>
    <div class="col-2">
        <button class="btn btn-danger btn-sm pl-delete-row"><i class="fas fa-times"></i></button>
    </div>
</div>