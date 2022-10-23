<?php

namespace App\Http\Controllers;

use App\Models\UserPrivilege;
use Illuminate\Http\Request;
use Validator;

class UserPrivilegeController extends Controller
{
    const blade_view = 'layout.user.privilege';
    const url_redirect ='user/privilege';
    const name = 'user privilege';
    const privilege = 'users';

    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.user.privilege',
        'url'       => 'user/privilege',
        'name'      => 'user privilege',
        'privilege' => 'users'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ONLY_SEE);

        $data = UserPrivilege::select('*');

        $search = $request->search;
        if (strlen($search) > 2) {
            $data = $data->where('name','LIKE','%'.$search.'%')
                ->orWhere('color','LIKE','%'.$search.'%');
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: null;
        $sort = $request->sort ?: null;
        $target = $request->target ?: null;

        if ($column && $sort) {
            $data = $data->orderBy($column, $sort);
        }

        $table = [
            'perPage'   => $perPage,
            'search'    => $search,
            'column'    => $column,
            'sort'      => $sort,
            'target'    => $target
        ];

        // penguraian data
        $params = [
            'data'      => $data->paginate($perPage)->appends($table),
            'type'      => 'data',
            'title'     => self::config['name'],
            'table'     => $table,
            'config'    => self::config
        ];

        // jika hanya ingin mendapatkan data table saja
        if ($target == 'table') {
            return view(self::config['blade'].'.table', $params);
        }
        return view(self::config['blade'].'.data', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $data = [
            'id'            => null,
            'name'          => null,
            'tickets'       => 0,
            'customers'     => 0,
            'products'      => 0,
            'reports'       => 0,
            'users'         => 0,
            'branches'      => 0,
            'warranties'    => 0,
            'states'        => 0,
            'color'         => 'primary'
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'      => $data,
            'type'      => 'create',
            'title'     => 'Create '.self::config['name'],
            'config'    => self::config
        ];

        return view(self::config['blade'].'.input', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        // validasi
        $rules = [
            'name'                  => 'required|min:3|max:100|unique:user_privileges,name',
        ];

        $messages = [
            'name.required'         => 'Nama wajib diisi',
            'name.min'              => 'Nama minimal 3 karakter',
            'name.max'              => 'Nama maksimal 100 karakter',
            'name.unique'           => 'Nama sudah terdaftar',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $hasil = UserPrivilege::create($request->all());

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserPrivilege  $userPrivilege
     * @return \Illuminate\Http\Response
     */
    public function show(UserPrivilege $userPrivilege)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserPrivilege  $userPrivilege
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPrivilege $userPrivilege, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        // penguraian data
        $params = [
            'data'      => $userPrivilege->find($id),
            'type'      => 'edit',
            'title'     => 'Edit Data '.self::config['name'],
            'config'    => self::config
        ];

        return view(self::config['blade'].'.input', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserPrivilege  $userPrivilege
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserPrivilege $userPrivilege, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        // validasi
        $rules = [
            'name'                  => 'required|min:3|max:100|unique:user_privileges,name,'.$id,
        ];

        $messages = [
            'name.required'         => 'Nama wajib diisi',
            'name.min'              => 'Nama minimal 3 karakter',
            'name.max'              => 'Nama maksimal 100 karakter',
            'name.unique'           => 'Nama sudah terdaftar',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $userPrivilege = $userPrivilege->find($id);
        $hasil = $userPrivilege->fill($request->all())->save();

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserPrivilege  $userPrivilege
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPrivilege $userPrivilege, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $hasil = UserPrivilege::find($id);
        $hasil->delete();

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ALL_ACCESS);

        $data = UserPrivilege::onlyTrashed();

        $search = $request->search;
        if (strlen($search) > 2) {
            $data = $data->where('name','LIKE','%'.$search.'%')
                ->orWhere('color','LIKE','%'.$search.'%');
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: null;
        $sort = $request->sort ?: null;
        $target = $request->target ?: null;

        if ($column && $sort) {
            $data = $data->orderBy($column, $sort);
        }

        $table = [
            'perPage'   => $perPage,
            'search'    => $search,
            'column'    => $column,
            'sort'      => $sort,
            'target'    => $target
        ];

        // penguraian data
        $params = [
            'data'  => $data->paginate($perPage)->appends($table),
            'type'  => 'trash',
            'title' => 'Trash',
            'table' => $table
        ];

        // jika hanya ingin mendapatkan data table saja
        if ($target == 'table') {
            return view(self::config['blade'].'.table', $params);
        }
        return view(self::config['blade'].'.data', $params);
    }

    public function restore($id = null)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ALL_ACCESS);

        if ($id != null){
            $hasil = UserPrivilege::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = UserPrivilege::onlyTrashed()->restore();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'restore', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }

    public function delete($id = null)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ALL_ACCESS);

        if ($id != null){
            $hasil = UserPrivilege::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = UserPrivilege::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }
}
