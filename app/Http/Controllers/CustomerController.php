<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // table
    const perPage = 10;

    // config
    const config = [
        'blade'     => 'layout.customer',
        'url'       => 'customer',
        'name'      => 'customer',
        'privilege' => 'customers'
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

        $data = Customer::select('*');

        $search = $request->search;
        if (strlen($search) > 1) {
            $data = $data->where('name','LIKE','%'.$search.'%')
                ->orWhere('phone', 'LIKE', '%'.$search.'%')
                ->orWhere('phone2', 'LIKE', '%'.$search.'%')
                ->orWhere('phone3', 'LIKE', '%'.$search.'%')
                ->orWhere('address', 'LIKE', '%'.$search.'%')
                ->orWhere('email', 'LIKE', '%'.$search.'%');

        }

        $perPage = $request->perPage ?: self::perPage;
        $column = $request->column ?: null;
        $sort = $request->sort ?: null;
        $target = $request->target ?: null;

        if ($column && $sort) {
            $data = $data->orderBy($column, $sort);
        }

        $table = [
            'perPage'   => $perPage,
            'search'    => $search,
            'column'    => $column,
            'sort'      => $sort,
            'target'    => $target
        ];

        // penguraian data
        $params = [
            'data'      => $data->paginate($perPage)->appends($table),
            'type'      => 'data',
            'title'     => self::config['name'],
            'table'     => $table,
            'config'    => self::config
        ];

        // jika hanya ingin mendapatkan data table saja
        if ($target == 'table') {
            return view(self::config['blade'].'.table', $params);
        }
        return view(self::config['blade'].'.data', $params);
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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
