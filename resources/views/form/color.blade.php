<label class="form-label">Warna Label</label>
<div>
    <input type="radio" class="btn-check" name="color" id="option-color-primary" value="primary" autocomplete="off" {{ old('color', $data->color) == 'primary' ? 'checked' : ''}}>
    <label class="btn btn-outline-primary rounded-5" for="option-color-primary">Primary</label>

    <input type="radio" class="btn-check" name="color" id="option-color-purple" value="purple" autocomplete="off" {{ old('color', $data->color) == 'purple' ? 'checked' : ''}}>
    <label class="btn btn-outline-purple rounded-5" for="option-color-purple">Purple</label>

    <input type="radio" class="btn-check" name="color" id="option-color-teal" value="teal" autocomplete="off" {{ old('color', $data->color) == 'teal' ? 'checked' : ''}}>
    <label class="btn btn-outline-teal rounded-5" for="option-color-teal">Teal</label>

    <input type="radio" class="btn-check" name="color" id="option-color-blue" value="blue" autocomplete="off" {{ old('color', $data->color) == 'blue' ? 'checked' : ''}}>
    <label class="btn btn-outline-blue rounded-5" for="option-color-blue">Blue</label>

    <input type="radio" class="btn-check" name="color" id="option-color-orange" value="orange" autocomplete="off" {{ old('color', $data->color) == 'orange' ? 'checked' : ''}}>
    <label class="btn btn-outline-orange rounded-5" for="option-color-orange">Orange</label>

    <input type="radio" class="btn-check" name="color" id="option-color-yellow" value="yellow" autocomplete="off" {{ old('color', $data->color) == 'yellow' ? 'checked' : ''}}>
    <label class="btn btn-outline-yellow rounded-5" for="option-color-yellow">Yellow</label>

    <input type="radio" class="btn-check" name="color" id="option-color-green" value="green" autocomplete="off" {{ old('color', $data->color) == 'green' ? 'checked' : ''}}>
    <label class="btn btn-outline-green rounded-5" for="option-color-green">Green</label>

    <input type="radio" class="btn-check" name="color" id="option-color-red" value="red" autocomplete="off" {{ old('color', $data->color) == 'red' ? 'checked' : ''}}>
    <label class="btn btn-outline-red rounded-5" for="option-color-red">Red</label>
</div>