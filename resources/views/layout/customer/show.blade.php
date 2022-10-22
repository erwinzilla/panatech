<span class="text-default">{{ $data->name}}</span>
<br><span>{{ $data->phone}}</span>
@if($data->address)
    <br><small class="text-muted">{{ $data->address}}</small>
@endif