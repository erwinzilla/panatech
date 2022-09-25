<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user');
    }

    public function branches()
    {
        return $this->belongsTo('App\Models\Branch', 'branch');
    }

    public function branch_coordinators()
    {
        return $this->belongsTo('App\Models\BranchCoordinator', 'branch_coordinator');
    }
}
