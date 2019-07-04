<div class="row mb-3 pl-add-row @isset($key) @if($errors->has("title.{$key}") || $errors->has("name.{$key}")) errors  @endif @endisset">
    <div class="col-5">
        <input type="text" name="title[]" class="form-control" placeholder="@lang('placeshub.wiki_title')" required="required">
    </div>
    <div class="col-5">
        <input type="text" name="name[]" class="form-control" placeholder="@lang('placeshub.name')">
    </div>
    <div class="col-2">
        <button class="btn btn-danger btn-sm pl-delete-row"><i class="fas fa-times"></i></button>
    </div>
</div>