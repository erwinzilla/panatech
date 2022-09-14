<label class="form-label">Access {{ ucwords($name) }}</label>
<div>
    <input type="radio" class="btn-check" name="{{ $name }}" id="option-{{ $name }}-0" value="0" {{ old($name, $data->$name) == 0 ? 'checked' : ''}}>
    <label class="btn btn-outline-danger rounded-5" for="option-{{ $name }}-0">@svg('heroicon-o-x-circle', 'icon-sm') Forbidden</label>

    <input type="radio" class="btn-check" name="{{ $name }}" id="option-{{ $name }}-1" value="1" autocomplete="off" {{ old($name, $data->$name) == 1 ? 'checked' : ''}}>
    <label class="btn btn-outline-primary rounded-5" for="option-{{ $name }}-1">@svg('heroicon-o-eye', 'icon-sm') Only See</label>

    <input type="radio" class="btn-check" name="{{ $name }}" id="option-{{ $name }}-2" value="2" autocomplete="off" {{ old($name, $data->$name) == 2 ? 'checked' : ''}}>
    <label class="btn btn-outline-primary rounded-5" for="option-{{ $name }}-2">@svg('heroicon-o-pencil-square', 'icon-sm') Can CRUD</label>

    <input type="radio" class="btn-check" name="{{ $name }}" id="option-{{ $name }}-3" value="3" autocomplete="off" {{ old($name, $data->$name) == 3 ? 'checked' : ''}}>
    <label class="btn btn-outline-success rounded-5" for="option-{{ $name }}-3">@svg('heroicon-o-sparkles', 'icon-sm') All Access</label>
</div>