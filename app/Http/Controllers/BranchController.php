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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // cek privilege
        privilegeLevel(self::privilege, ONLY_SEE);

        // penguraian data
        $params = [
            'data'  => Branch::all(),
            'type'  => 'data',
            'title' => self::name
        ];

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

    public function trash()
    {
        // cek privilege
        privilegeLevel(self::privilege, ALL_ACCESS);

        // penguraian data
        $params = [
            'data'  => Branch::onlyTrashed()->get(),
            'type'  => 'trash',
            'title' => 'Trash'
        ];

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
}
