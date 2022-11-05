<?php

namespace App\Http\Controllers;

use App\Models\JobPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class JobPartController extends Controller
{
    // config
    const config = [
        'privilege' => 'jobs'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $data = json_decode($request->data, true);
        foreach ($data as $i => $v){
            $obj[$i] = (object) $v;
        }

        for ($i = 0; $i < count($obj); $i++) {
            $hasil = JobPart::updateOrCreate(
                [
                    'job'           => (int)$obj[$i]->job,
                    'sku'           => $obj[$i]->sku,
                    'name'          => $obj[$i]->name,
                ],
                [
                    'price'         => (int)$obj[$i]->price,
                    'qty'           => (int)$obj[$i]->qty,
                    'created_by'    => Auth::user()->id,
                ]
            );
        }

        // add created by
        if ($hasil) {
            return response()->json([
                'status'    => 'success',
                'message'   => 'Sukses memasukan data',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobPart  $jobPart
     * @return \Illuminate\Http\Response
     */
    public function show(JobPart $jobPart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobPart  $jobPart
     * @return \Illuminate\Http\Response
     */
    public function edit(JobPart $jobPart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobPart  $jobPart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobPart $jobPart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobPart  $jobPart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $jobPart = JobPart::where('job', $request->job)
            ->where('sku', $request->sku)
            ->get();

        if ($jobPart->count() > 0) {
            $jobPart->first()->delete();

            // add created by
            if ($jobPart) {
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Sukses menghapus data',
                ]);
            }
        } else {
            return response()->json([
                'status'    => 'info',
                'message'   => 'Data belum terecord',
            ]);
        }
    }
}
