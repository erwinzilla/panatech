<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

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
        $parse  = $this->parseData(Ticket::select('tickets.*'), $request);

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
            'name'          => 'EZ-'.str_pad(Ticket::withTrashed()->get()->count() + 1, 6, '0', STR_PAD_LEFT),
            'service_info'  => null,
            'status'        => null,
            'customer_name' => null,
            'phone'         => null,
            'phone2'        => null,
            'phone3'        => null,
            'address'       => null,
            'email'         => null,
            'customer_type' => null,
            'model'         => null,
            'serial'        => null,
            'warranty_no'   => null,
            'purchase_date' => null,
            'warranty_type' => null,
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'              => $data,
            'data_additional'   => [
                'status'        => Status::all(),
                'customer_type' => CustomerType::all(),
            ],
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
            $hasil = Ticket::create($request->except(['flash-fill']));
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
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
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
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $warannty = Customer::find($id);

        // penguraian data
        $params = [
            'data'              => $ticket,
            'data_additional'   => [
                'status'        => Status::all(),
                'customer_type' => CustomerType::all(),
            ],
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
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        if ($this->validateInput($request, $ticket->id)){
            $hasil = $ticket->fill($request->except(['flash-fill']))->save();
        }

        // add updated by
        if ($hasil) {
            $ticket->update([
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
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        // update siapa yang menghapus
        $ticket->update([
            'deleted_by' => Auth::user()->id,
        ]);

        // send result
        $params = getStatus($ticket->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ONLY_SEE);

        // olah data
        $parse  = $this->parseData(Ticket::onlyTrashed()->select('tickets.*'), $request);

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
            $hasil = Ticket::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = Ticket::onlyTrashed()->restore();
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
            $hasil = Ticket::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = Ticket::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }

    public function parseData($data, $request)
    {
        // join
        $data = $data->leftJoin('states', 'tickets.status', '=', 'states.id');

        $search = $request->search;
        if (strlen($search) > 1) {
            $data = $data->where('tickets.name','LIKE','%'.$search.'%')
                ->orWhere('tickets.service_info', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.customer_name', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.phone', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.phone2', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.phone3', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.address', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.email', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.customer_type', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.model', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.serial', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.warranty_no', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.purchase_date', 'LIKE', '%'.$search.'%')
                ->orWhere('tickets.warranty_type', 'LIKE', '%'.$search.'%')
                ->orWhereHas('states', function ($q) use ($search) {
                    $q->where('states.name','LIKE','%'.$search.'%')
                        ->orWhere('states.color', 'LIKE', '%'.$search.'%');
                });
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
            'service_info'              => 'required|min:3',
            'customer_name'             => 'required|min:3|max:100',
            'phone'                     => 'required|min:3|max:100',
            'address'                   => 'required|min:3',
            'model'                     => 'required|min:3|max:100',
        ];

        $messages = [
            'service_info.required'     => 'Keterangan servis wajib diisi',
            'service_info.min'          => 'Keterangan servis minimal 3 karakter',
            'customer_name.required'    => 'Nama konsumen wajib diisi',
            'customer_name.min'         => 'Nama konsumen minimal 3 karakter',
            'customer_name.max'         => 'Nama konsumen maksimal 100 karakter',
            'phone.required'            => 'Nomor telp wajib diisi',
            'phone.min'                 => 'Nomor telp minimal 3 karakter',
            'phone.max'                 => 'Nomor telp maksimal 100 karakter',
            'address.required'          => 'Alamat wajib diisi',
            'address.min'               => 'Alamat minimal 3 karakter',
            'model.required'            => 'Model wajib diisi',
            'model.min'                 => 'Model minimal 3 karakter',
            'model.max'                 => 'Model maksimal 100 karakter',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all)->send();
        } else {
            return true;
        }
    }
}