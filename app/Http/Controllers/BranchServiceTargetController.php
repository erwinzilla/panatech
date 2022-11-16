<?php

namespace App\Http\Controllers;

use App\Models\BranchService;
use App\Models\BranchServiceTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class BranchServiceTargetController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.branch.service.target',
        'url'       => 'branch/service/target',
        'name'      => 'branch service target',
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
            'income_target'         => null,
            'income_div'            => null,
            'speed_repair_target'   => null,
            'speed_repair_div'      => null,
            'sabbr_target'          => null,
            'sabbr_div'             => null,
            'sabbr_max_result'      => null,
            'incentive'             => null,
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
            $hasil = BranchServiceTarget::create($request->all());
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BranchServiceTarget  $branchServiceTarget
     * @return \Illuminate\Http\Response
     */
    public function show(BranchServiceTarget $branchServiceTarget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BranchServiceTarget  $branchServiceTarget
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchService $target)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $data = BranchServiceTarget::where('branch_service', $target->id)->get()->first();

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
     * @param  \App\Models\BranchServiceTarget  $branchServiceTarget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchServiceTarget $target)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

//        $branchServiceTarget = BranchServiceTarget::where('branch_service', $target->id)->get()->first();

        if ($this->validateInput($request, $target->id)){
            $hasil = $target->fill($request->all())->save();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BranchServiceTarget  $branchServiceTarget
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchService $target)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $branchServiceTarget = BranchServiceTarget::where('branch_service', $target->id)->get()->first();

        // send result
        $params = getStatus($branchServiceTarget->delete() ? 'success' : 'error', 'delete', self::config['name']);

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
            'income_target'                 => 'required|numeric',
            'income_div'                    => 'required|numeric',
            'speed_repair_target'           => 'required|numeric',
            'speed_repair_div'              => 'required|numeric',
            'sabbr_target'                  => 'required|numeric',
            'sabbr_div'                     => 'required|numeric',
            'sabbr_max_result'              => 'required|numeric',
            'incentive'                     => 'required|numeric',
        ];

        $messages = [
            'income_target.required'        => 'Income target wajib diisi',
            'income_target.numeric'         => 'Income target hanya berupa angka',
            'income_div.required'           => 'Income target wajib diisi',
            'income_div.numeric'            => 'Income target hanya berupa angka',
            'speed_repair_target.required'  => 'Income target wajib diisi',
            'speed_repair_target.numeric'   => 'Income target hanya berupa angka',
            'speed_repair_div.required'     => 'Income target wajib diisi',
            'speed_repair_div.numeric'      => 'Income target hanya berupa angka',
            'sabbr_target.required'         => 'Income target wajib diisi',
            'sabbr_target.numeric'          => 'Income target hanya berupa angka',
            'sabbr_div.required'            => 'Income target wajib diisi',
            'sabbr_div.numeric'             => 'Income target hanya berupa angka',
            'sabbr_max_result.required'     => 'Income target wajib diisi',
            'sabbr_max_result.numeric'      => 'Income target hanya berupa angka',
            'incentive.required'            => 'Income target wajib diisi',
            'incentive.numeric'             => 'Income target hanya berupa angka',
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
