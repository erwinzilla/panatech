<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Warranty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class WarrantyController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.warranty',
        'url'       => 'warranty',
        'name'      => 'warranty',
        'privilege' => 'warranties'
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
        $parse  = $this->parseData(Warranty::select('warranties.*'), $request);

        // ambil data untuk form
        if ($parse['table']['type'] == 'form') {
            return responseJson($parse['data'], $parse['data']->with('customers'));
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
    public function create($id = null)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $data = [
            'id'            => null,
            'model'         => null,
            'serial'        => null,
            'warranty_no'   => null,
            'purchase_date' => null,
            'type'          => null,
            'customer'      => $id,
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'              => $data,
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
            $hasil = Warranty::create($request->all());
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
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function show(Warranty $warranty)
    {
        // penguraian data
//        $params = [
//            'data'      => $warranty,
//        ];

//        return view(self::config['blade'].'.show', $params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function edit(Warranty $warranty)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $warannty = Customer::find($id);

        // penguraian data
        $params = [
            'data'              => $warranty,
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
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warranty $warranty)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        if ($this->validateInput($request, $warranty->id)){
            $hasil = $warranty->fill($request->all())->save();
        }

        // add updated by
        if ($hasil) {
            $warranty->update([
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
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warranty $warranty)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        // update siapa yang menghapus
        $warranty->update([
            'deleted_by' => Auth::user()->id,
        ]);

        // send result
        $params = getStatus($warranty->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ONLY_SEE);

        // olah data
        $parse  = $this->parseData(Warranty::onlyTrashed()->select('warranties.*'), $request);

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
            $hasil = Warranty::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = Warranty::onlyTrashed()->restore();
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
            $hasil = Warranty::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = Warranty::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }

    public function parseData($data, $request)
    {
        // join
        $data = $data->leftJoin('customers', 'warranties.customer', '=', 'customers.id');

        $search = $request->search;
        if (strlen($search) > 1) {
            $data = $data->where('warranties.model','LIKE','%'.$search.'%')
                ->orWhere('warranties.serial', 'LIKE', '%'.$search.'%')
                ->orWhere('warranties.warranty_no', 'LIKE', '%'.$search.'%')
                ->orWhere('warranties.purchase_date', 'LIKE', '%'.$search.'%')
                ->orWhere('warranties.type', 'LIKE', '%'.$search.'%')
                ->orWhereHas('customers', function ($q) use ($search) {
                    $q->where('customers.name','LIKE','%'.$search.'%')
                        ->orWhere('customers.phone', 'LIKE', '%'.$search.'%')
                        ->orWhere('customers.phone2', 'LIKE', '%'.$search.'%')
                        ->orWhere('customers.phone3', 'LIKE', '%'.$search.'%')
                        ->orWhere('customers.address', 'LIKE', '%'.$search.'%')
                        ->orWhere('customers.email', 'LIKE', '%'.$search.'%')
                        ->orWhereHas('types', function ($q) use ($search) {
                            $q->where('customer_types.name','LIKE','%'.$search.'%');
                        });
                });
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: 'warranties.id';
        $sort = $request->sort ?: 'desc';
        $target = $request->target ?: 'data';
        $type = $request->type ?: 'data';

        // jika pilihannya ada choose
        if ($type == 'choose') {
            $target = 'table';
        }

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
            'model'             => 'required|min:3|max:100',
            'serial'            => 'required|min:3|max:100|unique:warranties,serial,'.$id,
        ];

        $messages = [
            'model.required'    => 'Model wajib diisi',
            'model.min'         => 'Model minimal 3 karakter',
            'model.max'         => 'Model maksimal 100 karakter',
            'serial.required'   => 'Nomor seri wajib diisi',
            'serial.min'        => 'Nomor seri minimal 3 karakter',
            'serial.max'        => 'Nomor seri maksimal 100 karakter',
            'serial.unique'     => 'Nomor seri sudah terpakai',
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
