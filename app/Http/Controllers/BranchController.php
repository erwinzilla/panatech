<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // cek privilege
        privilegeLevel('users', ONLY_SEE);

        // penguraian data
        $params = [
            'data'  => Branch::all(),
            'type'  => 'data',
            'title' => 'Branch'
        ];

        return view('layout.branch.data', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // cek privilege
        privilegeLevel('branches', CAN_CRUD);

        $data = [
            'id'        => null,
            'name'      => null,
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'  => $data,
            'type'  => 'create',
            'title' => 'Create Branch'
        ];

        return view('layout.branch.input', $params);
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
        privilegeLevel('branches', CAN_CRUD);

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

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses menambahkan user privilege'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal menambahkan user privilege'
            ];
        }

//        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'privilege', $params['status'] == 'success' ? $privilege->id : null);
        return redirect('branch')->with($params);
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
        privilegeLevel('branches', CAN_CRUD);

        // penguraian data
        $params = [
            'data'  => $branch,
            'type'  => 'edit',
            'title' => 'Edit Branch'
        ];

        return view('layout.branch.input', $params);
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
        privilegeLevel('branches', CAN_CRUD);

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

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses mengubah branch'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal mengubah branch'
            ];
        }

//        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'privilege', $id ? $id : null);
        return redirect('branch')->with($params);
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
        privilegeLevel('branches', CAN_CRUD);

        if($branch->delete()){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses menghapus branch'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal menghapus branch'
            ];
        }

//        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'privilege', $id ? $id : null);
        return redirect('branch')->with($params);
    }

    public function trash()
    {
        // cek privilege
        privilegeLevel('branches', ALL_ACCESS);

        // penguraian data
        $params = [
            'data'  => Branch::onlyTrashed()->get(),
            'type'  => 'trash',
            'title' => 'Trash'
        ];

        return view('layout.branch.data', $params);
    }

    public function restore($id = null)
    {
        // cek privilege
        privilegeLevel('branches', ALL_ACCESS);

        if ($id != null){
            $hasil = Branch::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = Branch::onlyTrashed()->restore();
        }

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses mengembalikan branch'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal mengembalikan branch'
            ];
        }
//        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'user', $id ? $id : null);
        return redirect('branch/trash')->with($params);
    }

    public function delete($id = null)
    {
        // cek privilege
        privilegeLevel('branches', ALL_ACCESS);

        if ($id != null){
            $hasil = Branch::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = Branch::onlyTrashed()->forceDelete();
        }

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses menghapus permanen branch'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal menghapus permanen branch'
            ];
        }
//        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'user', $id ? $id : null);
        return redirect('branch/trash')->with($params);
    }
}
