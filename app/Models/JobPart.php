<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jobs()
    {
        return $this->belongsTo('App\Models\Job', 'job');
    }
}
