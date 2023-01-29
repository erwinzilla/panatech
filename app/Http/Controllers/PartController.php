<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Validator;

class PartController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.part',
        'url'       => 'part',
        'name'      => 'Spare Part',
        'privilege' => 'parts'
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

//        $token = $request->session()->get('access_token');
//        $response = Http::withToken($token)->get(env('OAUTH_SERVER_URL').'/api/part');
//        return $response->json();

        // olah data
        $parse  = $this->parseData(Part::select('*'), $request, session('search'));

        // ambil data untuk form
        if ($parse['table']['type'] == 'form') {
            return responseJson($parse['data'], $parse['data']);
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
            'sku'       => null,
            'name'      => null,
            'price'     => 0,
            'loc'       => 'DPL01',
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
            $hasil = Part::create($request->all());
        }

        // add created by
        if ($hasil) {
            $hasil->update([
                'created_by' => Auth::user()->id,
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name'], $hasil->sku);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function show(Part $part)
    {
        if ($part) {
            return response()->json([
                'status'    => 'success',
                'message'   => 'Sukses mengambil data',
                'data'      => $part,
            ]);
        } else {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Data tidak ditemukan',
                'data'      => null,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function edit(Part $part)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customer = Customer::find($id);

        // penguraian data
        $params = [
            'data'              => $part,
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
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Part $part)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        if ($this->validateInput($request, $part->id)){
            $hasil = $part->fill($request->all())->save();
        }

        // add updated by
        if ($hasil) {
            $part->update([
                'updated_by' => Auth::user()->id,
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name'], $part->sku);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function destroy(Part $part)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        // update siapa yang menghapus
        $part->update([
            'deleted_by' => Auth::user()->id,
        ]);

        // send result
        $params = getStatus($part->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ALL_ACCESS);

        // olah data
        $parse  = $this->parseData(Part::onlyTrashed()->select('*'), $request);

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
            $hasil = Part::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = Part::onlyTrashed()->restore();
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
            $hasil = Part::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = Part::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }

    public function parseData($data, $request, $search = null)
    {
        if (!$search) {
            $search = $request->search;
        }
        if (strlen($search) > 1) {
            $data = $data->where('sku','LIKE','%'.$search.'%')
                ->orWhere('name', 'LIKE', '%'.$search.'%')
                ->orWhere('price', 'LIKE', '%'.$search.'%')
                ->orWhere('loc', 'LIKE', '%'.$search.'%');
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: 'id';
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
            'sku'                   => 'required|min:3|max:100|unique:parts,sku,'.$id,
            'name'                  => 'required|min:3|max:100',
            'price'                 => 'required|numeric',
        ];

        $messages = [
            'sku.required'          => 'SKU / Kode Part wajib diisi',
            'sku.min'               => 'SKU / Kode Part minimal 3 karakter',
            'sku.max'               => 'SKU / Kode Part maksimal 100 karakter',
            'sku.unique'            => 'SKU / Kode Part sudah terpakai',
            'name.required'         => 'Nama wajib diisi',
            'name.min'              => 'Nama minimal 3 karakter',
            'name.max'              => 'Nama maksimal 100 karakter',
            'price.required'        => 'Harga wajib diisi',
            'price.numeric'         => 'Harga harus terdiri dari angka',
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
