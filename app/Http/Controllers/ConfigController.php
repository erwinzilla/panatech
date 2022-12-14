<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Customer;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    // config
    const config = [
        'blade'     => 'layout.misc.config',
        'url'       => 'config',
        'name'      => 'configuration system',
        'privilege' => 'misc'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], ONLY_SEE);

        // penguraian data
        $params = [
            'data'      => Config::all(),
            'type'      => 'data',
            'title'     => 'Configuration System',
            'config'    => self::config
        ];

        // sesuaikan berdasarkan target
        return view(self::config['blade'].'.data', $params);
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
            'job_update_at'     => Carbon::now(),
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'              => $data,
            'data_additional'   => Status::all(),
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

        $hasil = Config::create($request->all());

        // add created by
        if ($hasil) {
            $hasil->update([
                'job_update_by' => Auth::user()->id,
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name']);

        return redirect(self::config['url'])->with($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function show(Config $config)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function edit(Config $config)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        // penguraian data
        $params = [
            'data'              => $config,
            'data_additional'   => Status::all(),
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
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Config $config)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $hasil = $config->fill($request->except(['validate', 'job_update_at']))->save();

        // convert date time
        $job_update_at = $request->job_update_at ? str_replace('/', '-', $request->job_update_at) : Carbon::now();

        // add updated by
        if ($hasil) {
            $config->update([
                'job_update_by' => Auth::user()->id,
                'job_update_at' => $job_update_at ? date('Y-m-d', strtotime($job_update_at)) : null,
            ]);
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name']);

        if ($request->validate) {
            return response()->json($params);
        }else {
            return redirect(self::config['url'])->with($params);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function destroy(Config $config)
    {
        //
    }
}
