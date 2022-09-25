@if($type == 'create')
    <form action="{{ url($config['url']) }}" method="post">
@endif
@if($type == 'edit')
    <form action="{{ url($config['url'].'/'.$data->id) }}" method="post">
    @method('put')
@endif
@csrf