<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function types()
    {
        return $this->belongsTo('App\Models\CustomerType', 'type');
    }

    function findOrCreate($data) {
        $record = Model::where('phone', $data['phone'])->first();
        if ($record) {
            return $record;
        } else {
            return Model::create($data);
        }
    }
}
