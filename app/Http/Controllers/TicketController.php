<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.ticket',
        'url'       => 'ticket',
        'name'      => 'ticket',
        'privilege' => 'tickets'
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
        $parse  = $this->parseData(Ticket::select('*'), $request);

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
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
        $search = $request->search;
        if (strlen($search) > 1) {
            $data = $data->where('name','LIKE','%'.$search.'%')
                ->orWhere('service_info', 'LIKE', '%'.$search.'%')
                ->orWhere('customer_name', 'LIKE', '%'.$search.'%')
                ->orWhere('phone', 'LIKE', '%'.$search.'%')
                ->orWhere('phone2', 'LIKE', '%'.$search.'%')
                ->orWhere('phone3', 'LIKE', '%'.$search.'%')
                ->orWhere('address', 'LIKE', '%'.$search.'%')
                ->orWhere('email', 'LIKE', '%'.$search.'%')
                ->orWhere('customer_type', 'LIKE', '%'.$search.'%')
                ->orWhere('model', 'LIKE', '%'.$search.'%')
                ->orWhere('serial', 'LIKE', '%'.$search.'%')
                ->orWhere('warranty_no', 'LIKE', '%'.$search.'%')
                ->orWhere('purchase_date', 'LIKE', '%'.$search.'%')
                ->orWhere('warranty_type', 'LIKE', '%'.$search.'%');
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: null;
        $sort = $request->sort ?: null;
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

    public function validateInput($request, $id = null)
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

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all)->send();
        } else {
            return true;
        }
    }
}
