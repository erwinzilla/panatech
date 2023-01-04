<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warranty extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function customers() {
        return $this->belongsTo('App\Models\Customer', 'customer');
    }

    function findOrCreate($data) {
        $record = Model::where('serial', $data['serial'])->first();
        if ($record) {
            return $record;
        } else {
            return Model::create($data);
        }
    }
}
