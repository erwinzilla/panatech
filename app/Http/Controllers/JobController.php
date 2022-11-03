<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\CustomerType;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Status;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class JobController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.job',
        'url'       => 'job',
        'name'      => 'job desk',
        'privilege' => 'jobs'
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
        $parse  = $this->parseData(Job::select('jobs.*'), $request, session('search'));

        // penguraian data
        $params = [
            'data'              => $parse['data']->paginate($parse['table']['perPage'])->appends($parse['table']),
            'data_additional'   => Config::all(),
            'type'              => $parse['table']['type'],
            'title'             => $parse['table']['type'] != 'choose' ? self::config['name'] : $parse['table']['type'],
            'table'             => $parse['table'],
            'config'            => self::config
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
        $ticket = null;
        if ($id) {
            $ticket = Ticket::find($id);
        }

        $data = [
            'id'                => null,
            'name'              => null,
            'invoice_name'      => null,
            'note'              => null,
            'accessories'       => null,
            'condition'         => null,
            'labour'            => null,
            'transport'         => null,
            'quality_report'    => null,
            'dealer_report'     => null,
            'repair_at'         => Carbon::now(),
            'collection_at'     => Carbon::now(),
            'actual_start_at'   => Carbon::now(),
            'actual_end_at'     => Carbon::now(),
            'service_info'      => $ticket ? $ticket->service_info : null,
            'repair_info'       => null,
            'status'            => $ticket ? $ticket->status : null,
            'customer_name'     => $ticket ? $ticket->customer_name : null,
            'phone'             => $ticket ? $ticket->phone : null,
            'phone2'            => $ticket ? $ticket->phone2 : null,
            'phone3'            => $ticket ? $ticket->phone3 : null,
            'address'           => $ticket ? $ticket->address : null,
            'email'             => $ticket ? $ticket->email : null,
            'customer_type'     => $ticket ? $ticket->customer_type : null,
            'tax_id'            => null,
            'model'             => $ticket ? $ticket->model : null,
            'serial'            => $ticket ? $ticket->serial : null,
            'warranty_no'       => $ticket ? $ticket->warranty_no : null,
            'purchase_date'     => $ticket ? $ticket->purchase_date : null,
            'warranty_type'     => $ticket ? $ticket->warranty_type : null,
            'branch_service'    => Auth::user()->branch_service,
            'job_type'          => null,
            'handle_by'         => Auth::user()->id,
            'ticket'            => $ticket ? $ticket->id : null,
            'ticket_name'       => $ticket ? $ticket->name : null,
            'created_at'        => Carbon::now(),
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'              => $data,
            'data_additional'   => [
                'status'        => Status::all(),
                'job_type'      => JobType::all(),
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
            $hasil = Job::create($request->except(['flash-fill', 'created_at', 'repair_at', 'collection_at', 'actual_start_at', 'actual_end_at', 'created_at_time', 'repair_at_time', 'collection_at_time', 'actual_start_at_time', 'actual_end_at_time', 'purchase_date']));
        }

        // convert date time
        $created_at = str_replace('/', '-', $request->created_at);
        $repair_at = str_replace('/', '-', $request->repair_at);
        $collection_at = str_replace('/', '-', $request->collection_at);
        $actual_start_at = str_replace('/', '-', $request->actual_start_at);
        $actual_end_at = str_replace('/', '-', $request->actual_end_at);
        $purchase_date = $request->purchase_date ? str_replace('/', '-', $request->purchase_date) : null;

        // add created and date
        if ($hasil) {
            $hasil->update([
                'created_by'        => Auth::user()->id,
                'created_at'        => date('Y-m-d', strtotime($created_at)).' '.$request->created_at_time.':00',
                'repair_at'         => date('Y-m-d', strtotime($repair_at)).' '.$request->repair_at_time.':00',
                'collection_at'     => date('Y-m-d', strtotime($collection_at)).' '.$request->collection_at_time.':00',
                'actual_start_at'   => date('Y-m-d', strtotime($actual_start_at)).' '.$request->actual_start_at_time.':00',
                'actual_end_at'     => date('Y-m-d', strtotime($actual_end_at)).' '.$request->actual_end_at_time.':00',
                'quality_report'    => $request->quality_report ? 1 : 0,
                'dealer_report'     => $request->dealer_report ? 1 : 0,
                'purchase_date'     => $purchase_date ? date('Y-m-d', strtotime($purchase_date)) : null,
            ]);
        }

        // update status on ticket
        if ($hasil->ticket) {
            $ticket = Ticket::find($hasil->ticket);
            $ticket->update([
                'status' => $hasil->status,
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name'], $hasil->name);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
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
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $warannty = Customer::find($id);

        // penguraian data
        $params = [
            'data'              => $job,
            'data_additional'   => [
                'status'        => Status::all(),
                'job_type'      => JobType::all(),
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
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        if ($this->validateInput($request, $job->id)){
            $hasil = $job->fill($request->except(['flash-fill', 'created_at', 'repair_at', 'collection_at', 'actual_start_at', 'actual_end_at', 'created_at_time', 'repair_at_time', 'collection_at_time', 'actual_start_at_time', 'actual_end_at_time']))->save();
        }

        // convert date time
        $created_at = str_replace('/', '-', $request->created_at);
        $repair_at = str_replace('/', '-', $request->repair_at);
        $collection_at = str_replace('/', '-', $request->collection_at);
        $actual_start_at = str_replace('/', '-', $request->actual_start_at);
        $actual_end_at = str_replace('/', '-', $request->actual_end_at);
        $purchase_date = $request->purchase_date ? str_replace('/', '-', $request->purchase_date) : null;

        // add created and date
        if ($hasil) {
            $job->update([
                'updated_by'        => Auth::user()->id,
                'created_at'        => date('Y-m-d', strtotime($created_at)).' '.$request->created_at_time.':00',
                'repair_at'         => date('Y-m-d', strtotime($repair_at)).' '.$request->repair_at_time.':00',
                'collection_at'     => date('Y-m-d', strtotime($collection_at)).' '.$request->collection_at_time.':00',
                'actual_start_at'   => date('Y-m-d', strtotime($actual_start_at)).' '.$request->actual_start_at_time.':00',
                'actual_end_at'     => date('Y-m-d', strtotime($actual_end_at)).' '.$request->actual_end_at_time.':00',
                'quality_report'    => $request->quality_report ? 1 : 0,
                'dealer_report'     => $request->dealer_report ? 1 : 0,
                'purchase_date'     => $purchase_date ? date('Y-m-d', strtotime($purchase_date)) : null,
            ]);
        }

        // update status on ticket
        if ($job->ticket) {
            $ticket = Ticket::find($job->ticket);
            $ticket->update([
                'status' => $job->status,
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name'], $job->name);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        // update siapa yang menghapus
        $job->update([
            'deleted_by' => Auth::user()->id,
        ]);

        // send result
        $params = getStatus($job->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ONLY_SEE);

        // olah data
        $parse  = $this->parseData(Job::onlyTrashed()->select('jobs.*'), $request);

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
            $hasil = Job::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = Job::onlyTrashed()->restore();
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
            $hasil = Job::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = Job::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }

    public function parseData($data, $request, $search = null)
    {
        // join
        $data = $data->leftJoin('branch_services', 'jobs.branch_service', '=', 'branch_services.id');
        $data = $data->leftJoin('job_types', 'jobs.job_type', '=', 'job_types.id');
        $data = $data->leftJoin('users', 'jobs.handle_by', '=', 'users.id');
        $data = $data->leftJoin('tickets', 'jobs.ticket', '=', 'tickets.id');

        if (!$search) {
            $search = $request->search;
        }
        if (strlen($search) > 1) {
            $data = $data->where('jobs.name','LIKE','%'.$search.'%')
                ->orWhere('jobs.invoice_name', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.note', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.accessories', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.condition', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.labour', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.transport', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.quality_report', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.dealer_report', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.repair_at', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.collection_at', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.actual_start_at', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.actual_end_at', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.service_info', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.repair_info', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.customer_name', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.phone', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.phone2', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.phone3', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.address', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.email', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.customer_type', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.tax_id', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.model', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.serial', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.warranty_no', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.purchase_date', 'LIKE', '%'.$search.'%')
                ->orWhere('jobs.warranty_type', 'LIKE', '%'.$search.'%')
                ->orWhereHas('job_types', function ($q) use ($search) {
                    $q->where('job_types.name','LIKE','%'.$search.'%')
                        ->orWhere('job_types.color', 'LIKE', '%'.$search.'%');
                })
                ->orWhereHas('states', function ($q) use ($search) {
                    $q->where('states.name','LIKE','%'.$search.'%')
                        ->orWhere('states.color', 'LIKE', '%'.$search.'%');
                })
                ->orWhereHas('handled', function ($q) use ($search) {
                    $q->where('users.name','LIKE','%'.$search.'%')
                        ->orWhere('users.email','LIKE','%'.$search.'%')
                        ->orWhere('users.username','LIKE','%'.$search.'%')
                        ->orWhere('users.address','LIKE','%'.$search.'%')
                        ->orWhere('users.phone','LIKE','%'.$search.'%')
                        ->orWhereHas('privileges', function ($q) use ($search) {
                            $q->where('user_privileges.name','LIKE','%'.$search.'%')
                                ->orWhere('user_privileges.color','LIKE','%'.$search.'%');
                        });
                })
                ->orWhereHas('tickets', function ($q) use ($search) {
                    $q->where('tickets.name','LIKE','%'.$search.'%')
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
                })
                ->orWhereHas('branch_services', function ($q) use ($search) {
                    $q->where('branch_services.name','LIKE','%'.$search.'%')
                        ->orWhere('branch_services.address', 'LIKE', '%'.$search.'%')
                        ->orWhere('branch_services.phone', 'LIKE', '%'.$search.'%')
                        ->orWhere('branch_services.fax', 'LIKE', '%'.$search.'%')
                        ->orWhere('branch_services.email', 'LIKE', '%'.$search.'%')
                        ->orWhereHas('users', function ($q) use ($search) {
                            $q->where('users.name','LIKE','%'.$search.'%')
                                ->orWhere('users.email','LIKE','%'.$search.'%')
                                ->orWhere('users.username','LIKE','%'.$search.'%')
                                ->orWhere('users.address','LIKE','%'.$search.'%')
                                ->orWhere('users.phone','LIKE','%'.$search.'%')
                                ->orWhereHas('privileges', function ($q) use ($search) {
                                    $q->where('user_privileges.name','LIKE','%'.$search.'%')
                                        ->orWhere('user_privileges.color', 'LIKE','%'.$search.'%');
                                });
                        })
                        ->orWhereHas('branches', function ($q) use ($search) {
                            $q->where('branches.name','LIKE','%'.$search.'%');
                        })
                        ->orWhereHas('branch_coordinators', function ($q) use ($search) {
                            $q->where('branch_coordinators.name','LIKE','%'.$search.'%')
                                ->orWhereHas('users', function ($q) use ($search) {
                                    $q->where('users.name','LIKE','%'.$search.'%')
                                        ->orWhere('users.email','LIKE','%'.$search.'%')
                                        ->orWhere('users.username','LIKE','%'.$search.'%')
                                        ->orWhere('users.address','LIKE','%'.$search.'%')
                                        ->orWhere('users.phone','LIKE','%'.$search.'%')
                                        ->orWhereHas('privileges', function ($q) use ($search) {
                                            $q->where('user_privileges.name','LIKE','%'.$search.'%')
                                                ->orWhere('user_privileges.color', 'LIKE','%'.$search.'%');
                                        });
                                });
                        });
                });
        }

        $filter = $request->filter ?: null;
        if ($filter) {
            $data = $data->whereNotIn('jobs.status', $filter);
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: 'jobs.id';
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
            'name'                      => 'required|min:3|max:100|unique:jobs,name,'.$id,
            'invoice_name'              => 'nullable|min:3|max:100|unique:jobs,invoice_name,'.$id,
            'service_info'              => 'required|min:3',
            'customer_name'             => 'required|min:3|max:100',
            'phone'                     => 'required|min:3|max:100',
            'address'                   => 'required|min:3',
            'model'                     => 'required|min:3|max:100',
        ];

        $messages = [
            'name.required'             => 'No. job wajib diisi',
            'name.min'                  => 'No. job minimal 3 karakter',
            'name.max'                  => 'No. job maksimal 100 karakter',
            'name.unique'               => 'No. job sudah terpakai',
            'invoice_name.min'          => 'No. invoice minimal 3 karakter',
            'invoice_name.max'          => 'No. invoice maksimal 100 karakter',
            'invoice_name.unique'       => 'No. invoice sudah terpakai',
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
