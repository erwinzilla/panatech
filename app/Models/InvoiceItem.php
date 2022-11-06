<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function invoices()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice');
    }

    public function getTotalAttribute()
    {
        return $this->price * $this->qty;
    }

    public function getGrandTotalAttribute()
    {
        return ($this->price * $this->qty) - (($this->price * $this->qty) * ($this->disc / 100));
    }
}
