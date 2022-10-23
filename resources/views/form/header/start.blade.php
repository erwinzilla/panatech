@if($type == 'create')
    <form action="{{ url($config['url']) }}" method="post" enctype="multipart/form-data">
@endif
@if($type == 'edit')
    <form action="{{ url($config['url'].'/'.$data->id) }}" method="post" enctype="multipart/form-data">
    @method('put')
@endif
@csrf