<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Job;
use App\Models\JobPart;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class InvoiceController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.invoice',
        'url'       => 'invoice',
        'name'      => 'invoice',
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
        $parse  = $this->parseData(Invoice::select('invoices.*'), $request, session('search'));

        // ambil data untuk form
//        if ($parse['table']['type'] == 'form') {
//            return responseJson($parse['data'], $parse['data']->with('types'));
//        }

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
    public function create($layout = 'input')
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $data = [
            'id'            => null,
            'name'          => null,
            'tax_rate'      => 11,
            'paid'          => null,
            'sub_total'     => 0,
            'tax_amount'    => 0,
            'grand_total'   => 0,
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'              => $data,
            'data_additional'   => null,
            'type'              => 'create',
            'title'             => 'Create '.self::config['name'],
            'config'            => self::config
        ];

        return view(self::config['blade'].'.'.$layout, $params);
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
            $hasil = Invoice::create($request->all());
        }

        // add created by
        if ($hasil) {
            $hasil->update([
                'created_by'    => Auth::user()->id,
                'paid'          => $request->paid ? 1 : 0,
                'paid_at'       => $request->paid ? Carbon::now() : null,
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name'], $hasil->phone);

        return redirect(self::config['url'].'/'.$hasil->id.'/edit')->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customer = Customer::find($id);

        // penguraian data
        $params = [
            'data'              => $invoice,
            'data_additional'   => InvoiceItem::where('invoice', $invoice->id)->get(),
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
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $customerType = CustomerType::find($id);

        if ($this->validateInput($request, $invoice->id)){
            $hasil = $invoice->fill($request->except(['target']))->save();
        }

        // add updated by
        if ($hasil) {
            $invoice->update([
                'updated_by'    => Auth::user()->id,
                'paid'          => $request->paid ? 1 : 0,
                'paid_at'       => $request->paid ? Carbon::now() : null,
            ]);
        }

        if ($request->target == 'job') {
            if ($invoice->paid) {
                // update status job
                $job = Job::find($invoice->job);
                $job->update([
                    'status' => Config::all()->first()->invoice_job_status_invoice,
                ]);

                // update status ticket
                if ($job->ticket) {
                    $ticket = Ticket::find($job->ticket);
                    $ticket->update([
                        'status' => $job->status,
                    ]);
                }
            }
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name'], $invoice->phone);
        if ($request->target == 'job') {
            return redirect('job/'.$invoice->job.'/edit')->with($params);
        }
        return redirect(self::config['url'])->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        // update siapa yang menghapus
        $invoice->update([
            'deleted_by' => Auth::user()->id,
        ]);

        // send result
        $params = getStatus($invoice->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ALL_ACCESS);

        // olah data
        $parse  = $this->parseData(Invoice::onlyTrashed()->select('invoices.*'), $request);

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
            $hasil = Invoice::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = Invoice::onlyTrashed()->restore();
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
            $hasil = Invoice::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = Invoice::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }

    public function parseData($data, $request, $search = null)
    {
        // join
        $data = $data->leftJoin('jobs', 'invoices.job', '=', 'jobs.id');

        if (!$search) {
            $search = $request->search;
        }
        if (strlen($search) > 1) {
            $data = $data->where('invoices.name','LIKE','%'.$search.'%')
                ->orWhere('invoices.paid', 'LIKE', '%'.$search.'%')
                ->orWhere('invoices.paid_at', 'LIKE', '%'.$search.'%')
                ->orWhere('invoices.tax_rate', 'LIKE', '%'.$search.'%')
                ->orWhereHas('jobs', function ($q) use ($search) {
                    $q->where('jobs.name','LIKE','%'.$search.'%')
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
                });
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: 'invoices.paid';
        $sort = $request->sort ?: 'asc';
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
            'name'                  => 'required|min:3|max:100|unique:invoices,name,'.$id,
            'tax_rate'              => 'required|numeric',
        ];

        $messages = [
            'name.required'         => 'Nama wajib diisi',
            'name.min'              => 'Nama minimal 3 karakter',
            'name.max'              => 'Nama maksimal 100 karakter',
            'name.unique'           => 'Nama sudah terpakai',
            'tax_rate.required'     => 'Nomor telp wajib diisi',
            'tax_rate.numeric'      => 'Nomor telp harus terdiri dari angka',
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateJob(Job $job)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $invoice = Invoice::where('job', $job->id)->first();
        $config = Config::all()->first();
        if ($invoice || $job->status != $config->invoice_job_status) {
            if ($invoice) {
                $message = 'invoice sudah pernah dibuat';
            }
            if ($job->status != $config->invoice_job_status) {
                $message = 'status harus complete terlebih dahulu';
            }
            // send result
            $params = getStatus('error', 'job', $message);
            return redirect('job/'.$job->id.'/edit')->with($params);
        }

        // generate invoice first
        $invoice = Invoice::create([
            'name'          => 'INV-JOB-'.str_pad(Invoice::withTrashed()->get()->count() + 1, 6, '0', STR_PAD_LEFT),
            'job'           => $job->id,
            'created_by'    => Auth::user()->id,
        ]);

        // generate invoice item
        if ($invoice) {
            // created labour
            if ($job->labour && $job->labour > 0) {
                InvoiceItem::create([
                    'invoice'   => $invoice->id,
                    'item'      => 'Labour',
                    'desc'      => 'Repair',
                    'price'     => $job->labour,
                    'qty'       => 1,
                    'disc'      => $job->warranty_type == IN_WARRANTY ? 100 : 0,
                ]);
            }
            // created transport
            if ($job->transport && $job->transport > 0) {
                InvoiceItem::create([
                    'invoice'   => $invoice->id,
                    'item'      => 'Transport',
                    'desc'      => 'Repair',
                    'price'     => $job->transport,
                    'qty'       => 1,
                    'disc'      => $job->warranty_type == IN_WARRANTY ? 100 : 0,
                ]);
            }
            // create job part
            $job_parts = JobPart::where('job', $job->id)->get();
            foreach ($job_parts as $row) {
                InvoiceItem::create([
                    'invoice'   => $invoice->id,
                    'item'      => $row->sku,
                    'desc'      => $row->name,
                    'price'     => $row->price,
                    'qty'       => $row->qty,
                    'disc'      => $job->warranty_type == IN_WARRANTY ? 100 : 0,
                ]);
            }
        }

        // send result
        $params = getStatus($invoice ? 'success' : 'error', 'create', self::config['name']);
        return redirect('job/'.$job->id.'/edit')->with($params);
    }
}
