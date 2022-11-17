<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchServiceSABBR extends Model
{
    use HasFactory;

    protected $table = 'branch_service_sabbr';
    protected $guarded = [];

    public function branch_services()
    {
        return $this->belongsTo('App\Models\BranchService', 'branch_service');
    }

    public function getTotalAttribute()
    {
        return ($this->open + $this->repair + $this->complete);
    }

    public function getRatesAttribute()
    {
        $sabbr = $this->total / ($this->total + $this->set_total);
        $target = BranchServiceTarget::where('branch_service', $this->branch_service)->get()->first();
        $result = $sabbr / ($target->sabbr_target / 100);
        $sum = $result > $target->sabbr_max_result ? $target->sabbr_max_result : $result;

        return $sum;
    }
}
