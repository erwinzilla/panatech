<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchCoordinator;
use App\Models\BranchService;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class BranchServiceController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.branch.service',
        'url'       => 'branch/service',
        'name'      => 'branch service',
        'privilege' => 'branches'
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
        $parse  = $this->parseData(BranchService::select('branch_services.*'), $request);

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
            'id'                    => null,
            'name'                  => null,
            'code'                  => null,
            'address'               => null,
            'phone'                 => null,
            'fax'                   => null,
            'email'                 => null,
            'user'                  => null,
            'branch'                => null,
            'branch_coordinator'    => null,
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'                      => $data,
            'data_additional'           => [
                'branch'                => Branch::all(),
                'branch_coordinator'    => BranchCoordinator::all(),
            ],
            'type'                      => 'create',
            'title'                     => 'Create '.self::config['name'],
            'config'                    => self::config
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
            $hasil = BranchService::create($request->all());
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
     * @param  \App\Models\BranchService  $branchService
     * @return \Illuminate\Http\Response
     */
    public function show(BranchService $branchService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BranchService  $branchService
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchService $branchService, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $branchService = BranchService::find($id);

        // penguraian data
        $params = [
            'data'                      => $branchService,
            'data_additional'           => [
                'branch'                => Branch::all(),
                'branch_coordinator'    => BranchCoordinator::all(),
            ],
            'type'                      => 'edit',
            'title'                     => 'Edit '.self::config['name'],
            'config'                    => self::config
        ];

        return view(self::config['blade'].'.input', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BranchService  $branchService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchService $branchService, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $branchService = BranchService::find($id);

        if ($this->validateInput($request, $branchService->id)){
            $hasil = $branchService->fill($request->all())->save();
        }

        // add updated by
        if ($hasil) {
            $branchService->update([
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
     * @param  \App\Models\BranchService  $branchService
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchService $branchService, $id)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $branchService = BranchService::find($id);

        // update siapa yang menghapus
        $branchService->update([
            'deleted_by' => Auth::user()->id,
        ]);

        // send result
        $params = getStatus($branchService->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    public function trash(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ALL_ACCESS);

        // olah data
        $parse  = $this->parseData(BranchService::onlyTrashed()->select('branch_services.*'), $request);

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
            $hasil = BranchService::onlyTrashed()
                ->where('id', $id)
                ->restore();
        } else {
            $hasil = BranchService::onlyTrashed()->restore();
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
            $hasil = BranchService::onlyTrashed()
                ->where('id', $id)
                ->forceDelete();
        } else {
            $hasil = BranchService::onlyTrashed()->forceDelete();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'delete permanent', self::config['name']);

        return redirect(self::config['url'].'/trash')->with($params);
    }

    public function parseData($data, $request)
    {
        // join
        $data = $data->leftJoin('branches', 'branch_services.branch', '=', 'branches.id');
        $data = $data->leftJoin('branch_coordinators', 'branch_services.branch_coordinator', '=', 'branch_coordinators.id');
        $data = $data->leftJoin('users', 'branch_services.user', '=', 'users.id');

        $search = $request->search;
        if (strlen($search) > 1) {
            $data = $data->where('branch_services.name','LIKE','%'.$search.'%')
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
        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: null;
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
            'name'                  => 'required|min:3|max:100|unique:branch_services,name,'.$id,
            'code'                  => 'required|min:3|max:50|unique:branch_services,code,'.$id,
            'address'               => 'required',
            'fax'                   => 'max:20|unique:branch_services,fax,'.$id,
        ];

        $messages = [
            'name.required'         => 'Nama wajib diisi',
            'name.min'              => 'Nama minimal 3 karakter',
            'name.max'              => 'Nama maksimal 100 karakter',
            'name.unique'           => 'Nama sudah terdaftar',
            'code.required'         => 'Kode servis wajib diisi',
            'code.min'              => 'Kode servis minimal 3 karakter',
            'code.max'              => 'Kode servis maksimal 50 karakter',
            'code.unique'           => 'Kode servis sudah terdaftar',
            'address.required'      => 'Alamat wajib diisi',
            'fax.max'               => 'Fax maksimal 20 karakter',
            'fax.unique'            => 'Fax sudah terdaftar',
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