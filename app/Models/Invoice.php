<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function jobs()
    {
        return $this->belongsTo('App\Models\Job', 'job');
    }

    public function getSubTotalAttribute()
    {
        $sum = 0;
        $data = InvoiceItem::where('invoice', $this->id)->get();
        foreach ($data as $row) {
            $sum += $row->grand_total;
        }

        return $sum;
    }

    public function getTaxAmountAttribute()
    {
        return $this->sub_total * ($this->tax_rate / 100);
    }

    public function getGrandTotalAttribute()
    {
        return $this->sub_total + $this->tax_amount;
    }

    public function getTotalItemAttribute()
    {
        return InvoiceItem::where('invoice', $this->id)->count();
    }

    public function getTotalNoDiscAttribute()
    {
        $sum = 0;
        $data = InvoiceItem::where('invoice', $this->id)->get();
        foreach ($data as $row) {
            $sum += $row->total;
        }

        return $sum;
    }
}
