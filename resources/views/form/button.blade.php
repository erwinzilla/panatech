<button type="reset" class="btn btn-outline-primary me-2">Reset</button>
<button {{ isset($name) ? 'name=submit value='.$name : '' }} type="submit" class="btn btn-primary">Save</button>