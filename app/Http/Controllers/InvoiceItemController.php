<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class InvoiceItemController extends Controller
{
    // config
    const config = [
        'blade'     => 'layout.invoice.item',
        'url'       => 'invoice/item',
        'name'      => 'invoice item',
        'privilege' => 'jobs'
    ];
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        $data = [
            'invoice'   => $request->invoice ?: null,
            'item'      => null,
            'desc'      => null,
            'price'     => null,
            'qty'       => 1,
            'disc'      => 0,
        ];

        $data = (object) $data;

        // penguraian data
        $params = [
            'data'      => $data,
            'type'      => 'create',
            'config'    => self::config
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
            $hasil = InvoiceItem::updateOrCreate(
                [
                    'invoice'   => $request->invoice,
                    'item'      => $request->item,
                ],
                [
                    'desc'      => $request->desc,
                    'qty'       => $request->qty,
                    'price'     => $request->price,
                    'disc'      => $request->disc,
                ]
                );
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'create', self::config['name']);

        return redirect('invoice/'.$hasil->invoice.'/edit')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceItem $item)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        // penguraian data
        $params = [
            'data'              => $item,
            'type'              => 'edit',
            'config'            => self::config
        ];

        return view(self::config['blade'].'.input', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceItem $item)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        if ($this->validateInput($request, $item->id)){
            $hasil = $item->fill($request->all())->save();
        }

        // send result
        $params = getStatus($hasil ? 'success' : 'error', 'update', self::config['name']);

        return redirect('invoice/'.$item->invoice.'/edit')->with($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceItem $item)
    {
        // cek privilege
        privilegeLevel(self::config['privilege'], CAN_CRUD);

        // get id invoice first
        $invoice = $item->invoice;

        // send result
        $params = getStatus($item->delete() ? 'success' : 'error', 'delete', self::config['name']);

        return redirect('invoice/'.$invoice.'/edit')->with($params);
    }

    public function validateInput(Request $request, $id = null)
    {
        // validasi
        $rules = [
            'item'              => 'required|min:3|max:100',
            'desc'              => 'required|min:3|max:100',
            'price'             => 'required|numeric',
            'qty'               => 'required|numeric',
            'disc'              => 'required|numeric',
        ];

        $messages = [
            'item.required'     => 'Nama item wajib diisi',
            'item.min'          => 'Nama item minimal 3 karakter',
            'item.max'          => 'Nama item maksimal 100 karakter',
            'desc.required'     => 'Deskripsi wajib diisi',
            'desc.min'          => 'Deskripsi minimal 3 karakter',
            'desc.max'          => 'Deskripsi maksimal 100 karakter',
            'price.required'    => 'Harga wajib diisi',
            'price.numeric'     => 'Harga harus terdiri dari angka',
            'qty.required'      => 'Kuantitas wajib diisi',
            'qty.numeric'       => 'Kuantitas harus terdiri dari angka',
            'disc.required'     => 'Diskon wajib diisi',
            'disc.numeric'      => 'Diskon harus terdiri dari angka',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // jika hanya validate input
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all)->send();
        } else {
            return true;
        }
    }
}
