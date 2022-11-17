<?php

namespace App\Http\Controllers;

use App\Models\BranchService;
use App\Models\BranchServiceSABBR;
use Illuminate\Http\Request;
use Validator;

class BranchServiceSABBRController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.branch.service.sabbr',
        'url'       => 'branch/service/sabbr',
        'name'      => 'branch service SABBR',
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
    public function create(BranchService $branchService)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $data = [
            'branch_service'        => $branchService->id,
            'id'                    => null,
            'open'                  => null,
            'repair'                => null,
            'complete'              => null,
            'set_total'             => null,
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'                      => $data,
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
            $hasil = BranchServiceSABBR::create($request->all());
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BranchServiceSABBR  $branchServiceSABBR
     * @return \Illuminate\Http\Response
     */
    public function show(BranchServiceSABBR $branchServiceSABBR)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BranchServiceSABBR  $branchServiceSABBR
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchService $sabbr)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $data = BranchServiceSABBR::where('branch_service', $sabbr->id)->get()->first();

        // penguraian data
        $params = [
            'data'      => $data,
            'type'      => 'edit',
            'title'     => 'Edit '.self::config['name'],
            'config'    => self::config
        ];

        return view(self::config['blade'].'.input', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BranchServiceSABBR  $branchServiceSABBR
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchServiceSABBR $sabbr)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        if ($this->validateInput($request, $sabbr->id)){
            $hasil = $sabbr->fill($request->all())->save();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BranchServiceSABBR  $branchServiceSABBR
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchService $sabbr)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $branchServiceSABBR = BranchServiceSABBR::where('branch_service', $sabbr->id)->get()->first();

        // send result
        $params = getStatus($branchServiceSABBR->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect(self::config['url'])->with($params);
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
            'open'                  => 'required|numeric',
            'repair'                => 'required|numeric',
            'complete'              => 'required|numeric',
            'set_total'             => 'required|numeric',
        ];

        $messages = [
            'open.required'         => 'Open set wajib diisi',
            'open.numeric'          => 'Open set hanya berupa angka',
            'repair.required'       => 'Repair set wajib diisi',
            'repair.numeric'        => 'Repair set hanya berupa angka',
            'complete.required'     => 'Complete set wajib diisi',
            'complete.numeric'      => 'Complete set hanya berupa angka',
            'set_total.required'    => 'Total set wajib diisi',
            'set_total.numeric'     => 'Total set hanya berupa angka',
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
