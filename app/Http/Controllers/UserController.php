<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPrivilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use File;
use App\Helpers;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // cek privilege
        privilegeLevel('users', 1);

        // penguraian data
        $params = [
            'data'  => User::all(),
            'type'  => 'data',
            'title' => 'User / Employee'
        ];

        return view('layout.user.data', $params);
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
            'name'              => null,
            'email'             => null,
            'email_verified_at' => null,
            'password'          => null,
            'username'          => null,
            'phone'             => null,
            'privilege'         => null,
            'address'           => null,
            'image'             => 'default.jpg'
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'  => $data,
            'data2' => UserPrivilege::all(),
            'type'  => 'create',
            'title' => 'Create User / Employee'
        ];

        return view('layout.user.input', $params);
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
        if( Auth::user()->privileges->users  < 2 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

        // validasi
        $rules = [
            'username'              => 'required|min:3|max:40|alpha_dash|unique:users,username',
            'email'                 => 'email|unique:users,email',
            'password'              => 'required|confirmed',
            'name'                  => 'required|min:3|max:100',
            'image'                 => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone'                 => 'numeric|unique:users,phone',
            'privilege'             => ['required', function ($attribute, $value, $fail) {
                if ($value === '0') {
                    $fail('Privilege ' . $attribute . ' is invalid.');
                }
            },
            ]
        ];

        $messages = [
            'username.required'     => 'Username wajib diisi',
            'username.min'          => 'Username minimal 3 karakter',
            'username.max'          => 'Username maksimal 40 karakter',
            'username.alpha_dash'   => 'Username tidak boleh mengandung unsur spasi',
            'username.unique'       => 'Username sudah terdaftar',
            'name.required'         => 'Nama Lengkap wajib diisi',
            'name.min'              => 'Nama lengkap minimal 3 karakter',
            'name.max'              => 'Nama lengkap maksimal 100 karakter',
            'email.email'           => 'Email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',
            'password.required'     => 'Password wajib diisi',
            'password.confirmed'    => 'Password tidak sama dengan konfirmasi password',
            'phone.numeric'         => 'Telepon harus teridiri dari angka',
            'phone.unique'          => 'Telepon sudah terdaftar',
            'privilege.required'    => 'Privilege wajib dipilih'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $filename = 'default.jpg';
        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/images/users'), $filename);
        }

        // tambah data
        $user = new User([
            'name'              => ucwords(strtolower($request->name)),
            'username'          => strtolower($request->username),
            'email'             => strtolower($request->email),
            'password'          => Hash::make($request->password),
            'phone'             => strval($request->phone),
            'address'           => ucwords(strtolower($request->address)),
            'privilege'         => $request->privilege,
            'email_verified_at' => \Carbon\Carbon::now(),
            'image'             => $filename
        ]);
        $hasil = $user->save();

        // User::create($request->all());

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses menambahkan user / employee'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal menambahkan user / employee'
            ];
        }

//        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'user', $params['status'] == 'success' ? $user->id : null);
        return redirect('user')->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // cari berdasarkan id atau username
        $data = User::where('id', $id)
            ->orWhere('username', $id)
            ->get()->first();

        // jika profile bukan dari sendiri
        if( $data->id != Auth::user()->id && Auth::user()->privileges->users  < 2 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

        // penguraian data
        $params = [
            'data'  => $data,
            'type'  => 'profile',
            'title' => ucwords($data->name)
        ];

        return view('layout.user.profile', $params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
            'data'  => User::find($id),
            'data2' => UserPrivilege::all(),
            'type'  => 'edit',
            'title' => 'Edit User / Employee'
        ];

        return view('layout.user.input', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
            'username'              => 'required|min:3|max:40|alpha_dash|unique:users,username,'.$id,
            'email'                 => 'email|unique:users,email,'.$id,
            'password'              => 'confirmed',
            'name'                  => 'required|min:3|max:100',
            'image'                 => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone'                 => 'numeric|unique:users,phone,'.$id,
            'privilege'             => ['required', function ($attribute, $value, $fail) {
                if ($value === '0') {
                    $fail('Privilege ' . $attribute . ' is invalid.');
                }
            },
            ]
        ];

        $messages = [
            'username.required'     => 'Username wajib diisi',
            'username.min'          => 'Username minimal 3 karakter',
            'username.max'          => 'Username maksimal 40 karakter',
            'username.alpha_dash'   => 'Username tidak boleh mengandung unsur spasi',
            'username.unique'       => 'Username sudah terdaftar',
            'name.required'         => 'Nama Lengkap wajib diisi',
            'name.min'              => 'Nama lengkap minimal 3 karakter',
            'name.max'              => 'Nama lengkap maksimal 100 karakter',
            'email.email'           => 'Email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',
            'password.required'     => 'Password wajib diisi',
            'password.confirmed'    => 'Password tidak sama dengan konfirmasi password',
            'phone.numeric'         => 'Telepon harus teridiri dari angka',
            'phone.unique'          => 'Telepon sudah terdaftar',
            'privilege.required'    => 'Privilege wajib dipilih'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $filename = User::find($id)->image;
        if ($request->hasFile('image')) {
            // hapus dulu photo yang lama
            $myFile = '/uploads/images/users/'.$filename;
            if (File::exists($myFile)) {
                File::delete($myFile);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/images/users'), $filename);
        }

        $hasil = User::where('id', $id)
            ->update([
                'name'      => ucwords(strtolower($request->name)),
                'username'  => strtolower($request->username),
                'email'     => strtolower($request->email),
                'phone'     => strval($request->phone),
                'address'   => ucwords(strtolower($request->address)),
                'privilege' => $request->privilege,
                'image'     => $filename
            ]);

        // cek jika password tidak kosong
        if ($request->password != '' && strlen($request->password) > 3) {
            $hasil = User::where('id', $id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);
        }

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses mengubah user'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal mengubah user'
            ];
        }
//        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'user', $id ? $id : null);
        return redirect('user')->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //tidak bisa diakses bila hak akses kurang dari 2::canedit
        if( Auth::user()->privileges->users  < 2 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

        $hasil = User::find($id);
        $hasil->delete();

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses menghapus user'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal menghapus user'
            ];
        }

//        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'user', $id ? $id : null);
        return redirect('user')->with($params);
    }

    public function trash(Request $request)
    {
        //tidak bisa diakses bila hak akses kurang dari 2::canedit
        if( Auth::user()->privileges->users  < 3 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('home')->with($params);
        }

        // penguraian data
        $params = [
            'data'  => User::onlyTrashed()->get(),
            'type'  => 'trash',
            'title' => 'Deleted User / Employee'
        ];

        return view('layout.user.data', $params);
    }

    public function restore($id = null)
    {
        //tidak bisa diakses bila hak akses kurang dari 3::allaccess
        if( PrivilegeHelp::getLevel(Auth::user()->privilege, 'a_users') < 3 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('dashboard')->with($params);
        }

        if ($id != null){
            $hasil = User::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = User::onlyTrashed()->restore();
        }

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses mengembalikan user'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal mengembalikan user'
            ];
        }
        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'user', $id ? $id : null);
        return redirect('user/trash')->with($params);
    }

    public function delete($id = null)
    {
        //tidak bisa diakses bila hak akses kurang dari 3::allaccess
        if( PrivilegeHelp::getLevel(Auth::user()->privilege, 'a_users') < 3 ) {
            $params = [
                'status'    => 'warning',
                'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
            ];
            return redirect('dashboard')->with($params);
        }

        if ($id != null){
            $hasil = User::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = User::onlyTrashed()->forceDelete();
        }

        if($hasil){
            $params = [
                'status'    => 'success',
                'message'   => 'Sukses menghapus permanen user'
            ];
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Gagal menghapus permanen user'
            ];
        }
        AddLog::add('<b>'.ucwords(Auth::user()->name).'</b> telah '.strtolower($params['message']), 'user', $id ? $id : null);
        return redirect('user/trash')->with($params);
    }
}
