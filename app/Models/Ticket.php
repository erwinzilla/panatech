<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function states() {
        return $this->belongsTo('App\Models\Status', 'status');
    }

    public function customer_types()
    {
        return $this->belongsTo('App\Models\CustomerType', 'customer_type');
    }
}
