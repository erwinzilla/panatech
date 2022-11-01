<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class CustomerController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.customer',
        'url'       => 'customer',
        'name'      => 'customer',
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
        $parse  = $this->parseData(Customer::select('customers.*'), $request);

        // ambil data untuk form
        if ($parse['table']['type'] == 'form') {
            return responseJson($parse['data'], $parse['data']->with('types'));
        }

        // penguraian data
        $params = [
            'data'      => $parse['data']->paginate($parse['table']['perPage'])->appends($parse['table']),
            'type'      => $parse['table']['type'],
            'title'     => $parse['table']['type'] != 'choose' ? self::config['name'] : $parse['table']['type'],
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
            'id'        => null,
            'name'      => null,
            'phone'     => null,
            'phone2'    => null,
            'phone3'    => null,
            'address'   => null,
            'email'     => null,
            'type'      => null,
            'tax_id'    => null,
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'              => $data,
            'data_additional'   => CustomerType::all(),
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

        if($this->validateInput($request)) {
            $hasil = Customer::create($request->all());
        }

        // add created by
        if ($hasil) {
            $hasil->update([
                'created_by' => Auth::user()->id,
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        // penguraian data
        $params = [
            'data'      => $customer,
        ];

        return view(self::config['blade'].'.show', $params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customer = Customer::find($id);

        // penguraian data
        $params = [
            'data'              => $customer,
            'data_additional'   => CustomerType::all(),
            'type'              => 'edit',
            'title'             => 'Edit '.self::config['name'],
            'config'            => self::config
        ];

        return view(self::config['blade'].'.input', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        if ($this->validateInput($request, $customer->id)){
            $hasil = $customer->fill($request->all())->save();
        }

        // add updated by
        if ($hasil) {
            $customer->update([
                'updated_by' => Auth::user()->id,
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        // update siapa yang menghapus
        $customer->update([
            'deleted_by' => Auth::user()->id,
        ]);

        // send result
        $params = getStatus($customer->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ALL_ACCESS);

        // olah data
        $parse  = $this->parseData(Customer::onlyTrashed()->select('customers.*'), $request);

        // penguraian data
        $params = [
            'data'      => $parse['data']->paginate($parse['table']['perPage'])->appends($parse['table']),
            'type'      => 'trash',
            'title'     => 'Trash',
            'table'     => $parse['table'],
            'config'    => self::config
        ];

        // sesuaikan berdasarkan target
        return view(self::config['blade'].'.'.$parse['table']['target'], $params);
    }

    public function restore($id = null)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ALL_ACCESS);

        if ($id != null){
            $hasil = Customer::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = Customer::onlyTrashed()->restore();
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
            $hasil = Customer::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = Customer::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }

    public function parseData($data, $request)
    {
        // join
        $data = $data->leftJoin('customer_types', 'customers.type', '=', 'customer_types.id');

        $search = $request->search;
        if (strlen($search) > 1) {
            $data = $data->where('customers.name','LIKE','%'.$search.'%')
                ->orWhere('customers.phone', 'LIKE', '%'.$search.'%')
                ->orWhere('customers.phone2', 'LIKE', '%'.$search.'%')
                ->orWhere('customers.phone3', 'LIKE', '%'.$search.'%')
                ->orWhere('customers.address', 'LIKE', '%'.$search.'%')
                ->orWhere('customers.email', 'LIKE', '%'.$search.'%')
                ->orWhereHas('types', function ($q) use ($search) {
                    $q->where('customer_types.name','LIKE','%'.$search.'%');
                });
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: 'customers.id';
        $sort = $request->sort ?: 'desc';
        $target = $request->target ?: 'data';
        $type = $request->type ?: 'data';

        // jika pilihannya ada choose
        if ($type == 'choose') {
            $target = 'table';
        }

        // sort by id
        if ($column && $sort) {
            $data = $data->orderBy($column, $sort);
        }

        $table = [
            'perPage'   => $perPage,
            'search'    => $search,
            'column'    => $column,
            'sort'      => $sort,
            'target'    => $target,
            'type'      => $type,
        ];

        return [
            'data'  => $data,
            'table' => $table
        ];
    }

    public function validateInput(Request $request, $id = null)
    {
        // validasi
        $rules = [
            'name'                  => 'required|min:3|max:100',
            'phone'                 => 'required|numeric|unique:customers,phone,'.$id,
            'email'                 => 'nullable|unique:customers,email,'.$id,
            'tax_id'                => 'nullable|unique:customers,tax_id,'.$id,
        ];

        $messages = [
            'name.required'         => 'Nama wajib diisi',
            'name.min'              => 'Nama minimal 3 karakter',
            'name.max'              => 'Nama maksimal 100 karakter',
            'phone.required'        => 'Nomor telp wajib diisi',
            'phone.numeric'         => 'Nomor telp harus terdiri dari angka',
            'phone.unique'          => 'Nomor telp sudah terpakai',
            'email.unique'          => 'Email telah terpakai',
            'tax_id.unique'         => 'NPWP / NIK telah terpakai',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // jika hanya validate input
        if ($request->validate) {
            return $validator->errors();
        }else{
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput($request->all)->send();
            } else {
                return true;
            }
        }
    }
}
