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
}
