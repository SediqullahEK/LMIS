<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
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
