<?php

namespace App\Models;

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

    public function handles() {
        return $this->belongsTo('App\Models\User', 'handle_by');
    }

    public function tickets() {
        return $this->belongsTo('App\Models\Ticket', 'ticket');
    }
}
