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
            'data'  => UserPrivilege::all(),
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
            'tickets'   => 0,
            'customers' => 0,
            'products'  => 0,
            'reports'   => 0,
            'users'     => 0,
            'branches'  => 0,
            'color'     => 'primary'
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
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::name);

        return redirect(self::url_redirect)->with($params);
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
        privilegeLevel(self::privilege, CAN_CRUD);

        // penguraian data
        $params = [
            'data'  => $userPrivilege->find($id),
            'type'  => 'edit',
            'title' => 'Edit Data '.self::name
        ];

        return view(self::blade_view.'.input', $params);
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
        privilegeLevel(self::privilege, CAN_CRUD);

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
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::name);

        return redirect(self::url_redirect)->with($params);
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
        privilegeLevel(self::privilege, CAN_CRUD);

        $hasil = UserPrivilege::find($id);
        $hasil->delete();

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete', self::name);

        return redirect(self::url_redirect)->with($params);
    }

    public function trash()
    {
        // cek privilege
        privilegeLevel(self::privilege, ALL_ACCESS);

        // penguraian data
        $params = [
            'data'  => UserPrivilege::onlyTrashed()->get(),
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
            $hasil = UserPrivilege::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = UserPrivilege::onlyTrashed()->restore();
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
            $hasil = UserPrivilege::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = UserPrivilege::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::name);

        return redirect(self::url_redirect.'/trash')->with($params);
    }

    public function get_table(Request $request)
    {
        /**
         * Parameters
         */
        $start = $request->start ? intval($request->start) : 0;
        $length = $request->length ? intval($request->length) : 10;
        $search = empty($request->searchQuery) || $request->searchQuery === "null" ? "" : $request->searchQuery;
        $sortColumnIndex = $request->sortColumn;
        $sortDirection = $request->sortDirection;
        $type = $request->type;

        /**
         * Raw Data
         */

        if ($type == 'data') {
            $list = UserPrivilege::select('*');
        }
        if ($type == 'trash') {
            $list = UserPrivilege::onlyTrashed();
        }

        // search
        if ($search) {
            $list = $list->where('name', 'like', '%'.$search.'%')
                ->orWhere('color', 'like', '%'.$search.'%');
        }
        $list = $list->get();

        $data = array();

        $no = $start;
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = [
                'data'          => $no,
                'attributes'    => [
                    'class'     => 'text-center text-muted w-1-slot'
                ]
            ];
            $row[] = $item->name;
            $row[] = $item->color ? '<span class="'.getBadge($item->color).'">'.ucwords($item->color).'</span>' : '<span>Unset</span>';
            if (getUserLevel('users') >= CAN_CRUD) {
                $row[] =  [
                    'data'          => '<div class="d-flex justify-content-center">
                            '.getButtonCRUD($type, self::url_redirect, $item->id).'
                          </div>',
                    'attributes'    => [
                        'class'     => 'w-2-slot'
                    ]
                ];
            };
            $data[] = $row;
        }

//        $data = json_decode(UserPrivilege::all()->toJson());

        /**
         * Search
         */
        $filtered = $data;

        /*
         * Sort
         */
        if (!is_null($sortColumnIndex) && $sortColumnIndex !== FALSE && $sortColumnIndex !== "null" && is_numeric($sortColumnIndex)) {
            array_multisort(array_column($filtered, $sortColumnIndex), ($sortDirection === "asc" ? SORT_ASC : SORT_DESC), $filtered);
        }


        /**
         * Slice
         */
        $response = array_slice($filtered, $start, $length);

        echo json_encode([
            "recordsTotal" => count($data),
            "recordsFiltered" => count($filtered),
            "data" => $response
        ]);
    }
}
