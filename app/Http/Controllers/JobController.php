<?php

namespace App\Http\Controllers;

use App\Models\Job;
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
        'name'      => 'job dek',
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
        $parse  = $this->parseData(Job::select('jobs.*'), $request);

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
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        //
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
        $data = $data->leftJoin('branch_services', 'job.branch_service', '=', 'branch_services.id');
        $data = $data->leftJoin('job_types', 'job.job_type', '=', 'job_types.id');
        $data = $data->leftJoin('users', 'job.handle_by', '=', 'users.id');
        $data = $data->leftJoin('tickets', 'job.ticket', '=', 'tickets.id');

        $search = $request->search;
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
                ->orWhereHas('handles', function ($q) use ($search) {
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
            $data = $data->whereNotIn('status', $filter);
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
