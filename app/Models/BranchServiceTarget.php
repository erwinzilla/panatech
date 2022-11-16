<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchServiceTarget extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function branch_services()
    {
        return $this->belongsTo('App\Models\BranchService', 'branch_service');
    }
}
