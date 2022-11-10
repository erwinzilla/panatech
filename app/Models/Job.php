<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function branch_services() {
        return $this->belongsTo('App\Models\BranchService', 'branch_service');
    }

    public function job_types() {
        return $this->belongsTo('App\Models\JobType', 'job_type');
    }

    public function states() {
        return $this->belongsTo('App\Models\Status', 'status');
    }

    public function handled() {
        return $this->belongsTo('App\Models\User', 'handle_by');
    }

    public function tickets() {
        return $this->belongsTo('App\Models\Ticket', 'ticket');
    }

    public function customer_types() {
        return $this->belongsTo('App\Models\CustomerType', 'customer_type');
    }

    public function getOnInvoiceAttribute()
    {
        $invoice = Invoice::where('job', $this->id)->first();
        return $invoice ? true : false;
    }

    public function getInvoiceAttribute()
    {
        $invoice = Invoice::where('job', $this->id)->first();
        return $invoice ? $invoice->id : null;
    }

    public function getInvoiceNameAttribute()
    {
        $invoice = Invoice::where('job', $this->id)->first();
        return $invoice ? $invoice->name : null;
    }

    public function getInvoicePaidAttribute()
    {
        $invoice = Invoice::where('job', $this->id)->first();
        return $invoice ? $invoice->paid : null;
    }

    public function getDayAttribute()
    {
        // jika bench servis
        if ($this->job_type == 1) {
            $to = Carbon::parse($this->created_at);
            $from = Carbon::parse($this->repair_at);
        }

        // jika home servis
        if ($this->job_type == 2) {
            $to = Carbon::parse($this->actual_start_at);
            $from = Carbon::parse($this->repair_at);
        }

        return $to->diffInDays($from);
    }
}
