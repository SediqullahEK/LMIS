<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PSStone extends Model
{
    protected $table = 'precious_semi_precious_stones';
    protected $fillable = [
        'name',
        'tin_num',
        'license_num',
        'province_id',
        'address',
        'created_by',
        'updated_by',
    ];
}
