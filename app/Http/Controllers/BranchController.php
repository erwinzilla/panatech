<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Validator;

class BranchController extends Controller
{
    const blade_view = 'layout.branch';
    const url_redirect ='branch';
    const name = 'branch';
    const privilege = 'branches';

    // table
    const perPage = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // cek privilege
        privilegeLevel(self::privilege, ONLY_SEE);

        $data = Branch::select('*');

        $search = $request->search;
        if (strlen($search) > 2) {
            $data = $data->where('name','LIKE','%'.$search.'%');
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
            'type'  => 'data',
            'title' => self::name,
            'table' => $table
        ];

        // jika hanya ingin mendapatkan data table saja
        if ($target == 'table') {
            return view(self::blade_view.'.table', $params);
        }
        return view(self::blade_view.'.data', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // cek privilege
        privilegeLevel(self::privilege, CAN_CRUD);

        $data = [
            'id'        => null,
            'name'      => null,
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'  => $data,
            'type'  => 'create',
            'title' => 'Create '.self::name
        ];

        return view(self::blade_view.'.input', $params);
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
        privilegeLevel(self::privilege, CAN_CRUD);

        // validasi
        $rules = [
            'name'                  => 'required|min:3|max:100|unique:branches,name',
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

        $hasil = Branch::create($request->all());

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::name);

        return redirect(self::url_redirect)->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        // cek privilege
        privilegeLevel(self::privilege, CAN_CRUD);

        // penguraian data
        $params = [
            'data'  => $branch,
            'type'  => 'edit',
            'title' => 'Edit '.self::name
        ];

        return view(self::blade_view.'.input', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {
        // cek privilege
        privilegeLevel(self::privilege, CAN_CRUD);

        // validasi
        $rules = [
            'name'                  => 'required|min:3|max:100|unique:branches,name,'.$branch->id,
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

        $hasil = $branch->fill($request->all())->save();

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::name);

        return redirect(self::url_redirect)->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        // cek privilege
        privilegeLevel(self::privilege, CAN_CRUD);

        // send result
        $params = getStatus($branch->delete() ? 'success' : 'error', 'delete', self::name);

        return redirect(self::url_redirect)->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::privilege, ONLY_SEE);

        $data = Branch::onlyTrashed();

        $search = $request->search;
        if (strlen($search) > 2) {
            $data = $data->where('name','LIKE','%'.$search.'%');
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
            return view(self::blade_view.'.table', $params);
        }
        return view(self::blade_view.'.data', $params);
    }

    public function restore($id = null)
    {
        // cek privilege
        privilegeLevel(self::privilege, ALL_ACCESS);

        if ($id != null){
            $hasil = Branch::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = Branch::onlyTrashed()->restore();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'restore', self::name);

        return redirect(self::url_redirect.'/trash')->with($params);
    }

    public function delete($id = null)
    {
        // cek privilege
        privilegeLevel(self::privilege, ALL_ACCESS);

        if ($id != null){
            $hasil = Branch::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = Branch::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::name);

        return redirect(self::url_redirect.'/trash')->with($params);
    }

    public function choose(Request $request)
    {
        // cek privilege
        privilegeLevel(self::privilege, ONLY_SEE);

        $data = Branch::select('*');

        $search = $request->search;
        if (strlen($search) > 1) {
            $data = $data->where('name','LIKE','%'.$search.'%');
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
            'type'  => 'choose',
            'title' => 'Choose',
            'table' => $table
        ];

        // jika hanya ingin mendapatkan data table saja
        if ($target == 'table') {
            return view(self::blade_view.'.table', $params);
        }
        return view(self::blade_view.'.data', $params);
    }
}
