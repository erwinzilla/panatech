<?php

namespace App\Http\Controllers;

use App\Models\UserPrivilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserPrivilegeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //tidak bisa diakses bila hak akses kurang dari 1::onlysee
        if( Auth::user()->privileges->users  < 1 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

        // penguraian data
        $params = [
            'data'  => UserPrivilege::all(),
            'type'  => 'data'
        ];

        return view('user.privilege.data', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //tidak bisa diakses bila hak akses kurang dari 2::canedit
        if( Auth::user()->privileges->users  < 2 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

        $data = [
            'id'        => null,
            'name'      => null,
            'tickets'   => 0,
            'customers' => 0,
            'products'  => 0,
            'reports'   => 0,
            'users'     => 0,
            'color'     => 'primary'
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'  => $data,
            'type'  => 'create'
        ];

        return view('user.privilege.input', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //tidak bisa diakses bila hak akses kurang dari 2::canedit
        if( Auth::user()->privileges->users < 2 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

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
        return redirect('user/privilege')->with($params);
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
        //tidak bisa diakses bila hak akses kurang dari 2::canedit
        if( Auth::user()->privileges->users  < 2 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

        // penguraian data
        $params = [
            'data'  => $userPrivilege->find($id),
            'type'  => 'edit'
        ];

        return view('user.privilege.input', $params);
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
        //tidak bisa diakses bila hak akses kurang dari 2::canedit
        if( Auth::user()->privileges->users  < 2 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

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

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses mengubah user privilege'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal mengubah user privilege'
            ];
        }

//        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'privilege', $id ? $id : null);
        return redirect('user/privilege')->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserPrivilege  $userPrivilege
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPrivilege $userPrivilege, $id)
    {
        //tidak bisa diakses bila hak akses kurang dari 2::canedit
        if( Auth::user()->privileges->users  < 2 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

        $hasil = UserPrivilege::find($id);
        $hasil->delete();

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses menghapus user privilege'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal menghapus user privilege'
            ];
        }

//        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'privilege', $id ? $id : null);
        return redirect('user/privilege')->with($params);
    }

    public function trash()
    {
        //tidak bisa diakses bila hak akses kurang dari 1::onlysee
        if( Auth::user()->privileges->users  < 1 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

        // penguraian data
        $params = [
            'data'  => UserPrivilege::onlyTrashed()->get(),
            'type'  => 'trash',
        ];

        return view('user.privilege.data', $params);
    }
}
