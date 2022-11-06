<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function job_users()
    {
        return $this->belongsTo('App\Models\User', 'job_update_by');
    }

    public function invoice_job_states()
    {
        return $this->belongsTo('App\Models\Status', 'invoice_job_status');
    }

    public function invoice_job_status_invoices()
    {
        return $this->belongsTo('App\Models\Status', 'invoice_job_status_invoice');
    }
}
