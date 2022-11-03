<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Warranty;
use Carbon\Carbon;
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
        $parse  = $this->parseData(Ticket::select('tickets.*'), $request, session('search'));

        // ambil data untuk form
        if ($parse['table']['type'] == 'form') {
            return responseJson($parse['data'], $parse['data']->with('states'));
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

        // cari data berdasarkan warranty
        $warranty = null;
        if ($id) {
            $warranty = Warranty::find($id);
        }

        $data = [
            'id'            => null,
            'name'          => 'PT-'.str_pad(Ticket::withTrashed()->get()->count() + 1, 6, '0', STR_PAD_LEFT),
            'service_info'  => null,
            'note'          => null,
            'status'        => null,
            'customer_name' => $warranty ? $warranty->customers->name : null,
            'phone'         => $warranty ? $warranty->customers->phone : null,
            'phone2'        => $warranty ? $warranty->customers->phone2 : null,
            'phone3'        => $warranty ? $warranty->customers->phone3 : null,
            'address'       => $warranty ? $warranty->customers->address : null,
            'email'         => $warranty ? $warranty->customers->email : null,
            'customer_type' => $warranty ? $warranty->customers->type : null,
            'model'         => $warranty ? $warranty->model : null,
            'serial'        => $warranty ? $warranty->serial : null,
            'warranty_no'   => $warranty ? $warranty->warranty_no : null,
            'purchase_date' => $warranty ? $warranty->purchase_date : null,
            'warranty_type' => $warranty ? $warranty->type : null,
            'branch_service'=> Auth::user()->branch_service,
            'created_at'    => Carbon::now(),
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
            $hasil = Ticket::create($request->except(['flash-fill', 'created_at', 'purchase_date']));
        }

        // convert date time
        $created_at = $request->created_at ? str_replace('/', '-', $request->created_at) : Carbon::now();
        $purchase_date = $request->purchase_date ? str_replace('/', '-', $request->purchase_date) : null;

        // add created by
        if ($hasil) {
            $hasil->update([
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d', strtotime($created_at)),
                'purchase_date' => date('Y-m-d', strtotime($purchase_date)),
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name'], $hasil->name);

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
            $hasil = $ticket->fill($request->except(['flash-fill', 'created_at', 'purchase_date']))->save();
        }

        // convert date time
        $created_at = $request->created_at ? str_replace('/', '-', $request->created_at) : Carbon::now();
        $purchase_date = $request->purchase_date ? str_replace('/', '-', $request->purchase_date) : null;

        // add updated by
        if ($hasil) {
            $ticket->update([
                'updated_by'    => Auth::user()->id,
                'created_at'    => $created_at ? date('Y-m-d', strtotime($created_at)) : null,
                'purchase_date' => $purchase_date ? date('Y-m-d', strtotime($purchase_date)) : null,
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name'], $ticket->name);

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

    public function parseData($data, $request, $search = null)
    {
        // join
        $data = $data->leftJoin('states', 'tickets.status', '=', 'states.id');

        if (!$search) {
            $search = $request->search;
        }
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

        $filter = $request->filter ?: null;
        if ($filter) {
            $data = $data->whereNotIn('status', $filter);
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: 'tickets.id';
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
