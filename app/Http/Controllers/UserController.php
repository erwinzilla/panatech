<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPrivilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use File;

class UserController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.user',
        'url'       => 'user',
        'name'      => 'user / employee',
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

        $data = User::select('users.*');

        // join
        $data = $data->join('user_privileges', 'users.privilege', '=', 'user_privileges.id');

        $search = $request->search;
        if (strlen($search) > 0) {
            $data = $data->where('users.name','LIKE','%'.$search.'%')
                ->orWhere('users.email','LIKE','%'.$search.'%')
                ->orWhere('users.username','LIKE','%'.$search.'%')
                ->orWhere('users.address','LIKE','%'.$search.'%')
                ->orWhere('users.phone','LIKE','%'.$search.'%')
                ->orWhereHas('privileges', function ($q) use ($search) {
                    $q->where('user_privileges.name','LIKE','%'.$search.'%')
                    ->orWhere('user_privileges.color','LIKE','%'.$search.'%');
                });
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
            'data'                  => $data,
            'data_additional'       => [
                'user_privilege'    => UserPrivilege::all()
            ],
            'type'                  => 'create',
            'title'                 => 'Create '.self::config['name'],
            'config'                => self::config
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

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name']);

        return redirect(self::config['url'])->with($params);
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
        if( $data->id != Auth::user()->id && getUserLevel(self::config['privilege'])  < CAN_CRUD ) {
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

        return view(self::config['blade'].'.profile', $params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        // penguraian data
        $params = [
            'data'  => User::find($id),
            'data2' => UserPrivilege::all(),
            'type'  => 'edit',
            'title' => 'Edit '.self::config['name']
        ];

        return view(self::config['blade'].'.input', $params);
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
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

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

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $hasil = User::find($id);
        $hasil->delete();

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ALL_ACCESS);

        $data = User::onlyTrashed();

        $search = $request->search;
        if (strlen($search) > 0) {
            $data = $data->where('users.name','LIKE','%'.$search.'%')
                ->orWhere('users.email','LIKE','%'.$search.'%')
                ->orWhere('users.username','LIKE','%'.$search.'%')
                ->orWhere('users.address','LIKE','%'.$search.'%')
                ->orWhere('users.phone','LIKE','%'.$search.'%')
                ->orWhereHas('privileges', function ($q) use ($search) {
                    $q->where('user_privileges.name','LIKE','%'.$search.'%')
                        ->orWhere('user_privileges.color','LIKE','%'.$search.'%');
                });
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
            $hasil = User::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = User::onlyTrashed()->restore();
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
            $hasil = User::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = User::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }

    public function chooseUser(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ONLY_SEE);

        $data = User::select('*');

        $search = $request->search;
        if (strlen($search) > 0) {
            $data = $data->where('users.name','LIKE','%'.$search.'%')
                ->orWhere('users.email','LIKE','%'.$search.'%')
                ->orWhere('users.username','LIKE','%'.$search.'%')
                ->orWhere('users.address','LIKE','%'.$search.'%')
                ->orWhere('users.phone','LIKE','%'.$search.'%')
                ->orWhereHas('privileges', function ($q) use ($search) {
                    $q->where('user_privileges.name','LIKE','%'.$search.'%')
                        ->orWhere('user_privileges.color','LIKE','%'.$search.'%');
                });
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
            return view(self::config['blade'].'.table', $params);
        }
        return view(self::config['blade'].'.data', $params);
    }
}
