<?php

namespace App\Http\Controllers;

use App\Models\BranchCoordinator;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class BranchCoordinatorController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.branch.coordinator',
        'url'       => 'branch/coordinator',
        'name'      => 'branch coordinator',
        'privilege' => 'branches'
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

        $data = BranchCoordinator::select('branch_coordinators.*');

        // join
        $data = $data->join('users', 'branch_coordinators.user', '=', 'users.id');

        $search = $request->search;
        if (strlen($search) > 2) {
            $data = $data->where('branch_coordinators.name','LIKE','%'.$search.'%')
                ->orWhereHas('users', function ($q) use ($search) {
                    $q->where('users.name','LIKE','%'.$search.'%')
                        ->orWhere('users.email','LIKE','%'.$search.'%')
                        ->orWhere('users.username','LIKE','%'.$search.'%')
                        ->orWhere('users.address','LIKE','%'.$search.'%')
                        ->orWhere('users.phone','LIKE','%'.$search.'%')
                        ->orWhereHas('privileges', function ($q) use ($search) {
                            $q->where('user_privileges.name','LIKE','%'.$search.'%')
                                ->orWhere('user_privileges.color', 'LIKE','%'.$search.'%');
                        });
                });
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: null;
        $sort = $request->sort ?: null;
        $target = $request->target ?: null;

        if ($column && $sort) {
            $data = $data->orderBy($column, $sort);
        }
        
        // penguraian table
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
            'id'        => null,
            'name'      => null,
            'user'      => null
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'              => $data,
            'data_additional'   => [
                'user'          => User::all(),
            ],
            'type'              => 'create',
            'title'             => 'Create '.self::config['name'],
            'config'            => self::config
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
            'name'                  => 'required|min:3|max:100|unique:branch_coordinators,name',
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

        $hasil = BranchCoordinator::create($request->all());

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BranchCoordinator  $branchCoordinator
     * @return \Illuminate\Http\Response
     */
    public function show(BranchCoordinator $branchCoordinator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BranchCoordinator  $branchCoordinator
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchCoordinator $branchCoordinator, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $branchCoordinator = BranchCoordinator::find($id);

        // penguraian data
        $params = [
            'data'              => $branchCoordinator,
            'data_additional'   => [
                'user'          => User::all(),
            ],
            'type'              => 'edit',
            'title'             => 'Create '.self::config['name'],
            'config'            => self::config
        ];

        return view(self::config['blade'].'.input', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BranchCoordinator  $branchCoordinator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchCoordinator $branchCoordinator, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $branchCoordinator = BranchCoordinator::find($id);

        // validasi
        $rules = [
            'name'                  => 'required|min:3|max:100|unique:branches,name,'.$branchCoordinator->id,
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

        $hasil = $branchCoordinator->fill($request->all())->save();

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BranchCoordinator  $branchCoordinator
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchCoordinator $branchCoordinator, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $branchCoordinator = BranchCoordinator::find($id);

        // send result
        $params = getStatus($branchCoordinator->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ONLY_SEE);

        $data = BranchCoordinator::onlyTrashed()->select('branch_coordinators.*');

        // join
        $data = $data->join('users', 'branch_coordinators.user', '=', 'users.id');

        $search = $request->search;
        if (strlen($search) > 2) {
            $data = $data->where('branch_coordinators.name','LIKE','%'.$search.'%')
                ->orWhereHas('users', function ($q) use ($search) {
                    $q->where('users.name','LIKE','%'.$search.'%')
                        ->orWhere('users.email','LIKE','%'.$search.'%')
                        ->orWhere('users.username','LIKE','%'.$search.'%')
                        ->orWhere('users.address','LIKE','%'.$search.'%')
                        ->orWhere('users.phone','LIKE','%'.$search.'%')
                        ->orWhereHas('privileges', function ($q) use ($search) {
                            $q->where('user_privileges.name','LIKE','%'.$search.'%')
                                ->orWhere('user_privileges.color', 'LIKE','%'.$search.'%');
                        });
                });
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: null;
        $sort = $request->sort ?: null;
        $target = $request->target ?: null;

        if ($column && $sort) {
            $data = $data->orderBy($column, $sort);
        }

        // penguraian table
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
            'type'      => 'trash',
            'title'     => 'Trash',
            'table'     => $table,
            'config'    => self::config
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
            $hasil = BranchCoordinator::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = BranchCoordinator::onlyTrashed()->restore();
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
            $hasil = BranchCoordinator::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = BranchCoordinator::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }
}
