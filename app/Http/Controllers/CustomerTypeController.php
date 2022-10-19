<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use Illuminate\Http\Request;
use Validator;

class CustomerTypeController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.customer.type',
        'url'       => 'customer/type',
        'name'      => 'customer type',
        'privilege' => 'customers'
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

        // olah data
        $parse  = $this->parseData(CustomerType::select('*'), $request);

        // penguraian data
        $params = [
            'data'      => $parse['data']->paginate($parse['table']['perPage'])->appends($parse['table']),
            'type'      => 'data',
            'title'     => self::config['name'],
            'table'     => $parse['table'],
            'config'    => self::config
        ];

        // sesuaikan berdasarkan target
        return view(self::config['blade'].'.'.$parse['table']['target'], $params);
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
            'name' => null,
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

        if($this->validateInput($request)) {
            $hasil = CustomerType::create($request->all());
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerType  $customerType
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerType $customerType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerType  $customerType
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerType $customerType, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $customerType = CustomerType::find($id);

        // penguraian data
        $params = [
            'data'      => $customerType,
            'type'      => 'edit',
            'title'     => 'Edit '.self::config['name'],
            'config'    => self::config
        ];

        return view(self::config['blade'].'.input', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerType  $customerType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerType $customerType, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $customerType = CustomerType::find($id);

        if ($this->validateInput($request, $id)){
            $hasil = $customerType->fill($request->all())->save();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerType  $customerType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerType $customerType, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $customerType = CustomerType::find($id);

        // send result
        $params = getStatus($customerType->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ONLY_SEE);

        // olah data
        $parse  = $this->parseData(CustomerType::onlyTrashed(), $request);

        // penguraian data
        $params = [
            'data'      => $parse['data']->paginate($parse['table']['perPage'])->appends($parse['table']),
            'type'      => 'trash',
            'title'     => 'Trash',
            'table'     => $parse['table'],
            'config'    => self::config
        ];

        // jika hanya ingin mendapatkan data table saja
        if ($parse['table']['target'] == 'table') {
            return view(self::config['blade'].'.table', $params);
        }
        return view(self::config['blade'].'.data', $params);
    }

    public function restore($id = null)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ALL_ACCESS);

        if ($id != null){
            $hasil = CustomerType::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = CustomerType::onlyTrashed()->restore();
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
            $hasil = CustomerType::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = CustomerType::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }

    public function parseData($data, $request)
    {
        $search = $request->search;
        if (strlen($search) > 1) {
            $data = $data->where('name','LIKE','%'.$search.'%');
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: null;
        $sort = $request->sort ?: null;
        $target = $request->target ?: 'data';

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

        return [
            'data'  => $data,
            'table' => $table
        ];
    }

    public function validateInput($request, $id = null)
    {
        // validasi
        $rules = [
            'name'                  => 'required|min:3|max:100|unique:customer_types,name,'.$id,
        ];

        $messages = [
            'name.required'         => 'Nama wajib diisi',
            'name.min'              => 'Nama minimal 3 karakter',
            'name.max'              => 'Nama maksimal 100 karakter',
            'name.unique'           => 'Nama sudah terdaftar',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all)->send();
        } else {
            return true;
        }
    }
}
