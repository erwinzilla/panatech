<button type="reset" class="btn btn-outline-primary me-2 {{ isset($class) ? $class : ''}}">Reset</button>
<button {{ isset($name) ? 'name=submit value='.$name : '' }} type="submit" class="btn btn-primary {{ isset($class) ? $class : ''}}">Save</button>