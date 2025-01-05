<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    protected $fillable = [
        'name',
        'f_name',
        'photo_path',
        'tin_num',
        'tazkira_num',
        'province_id',
        'district',
        'date_of_birth',
        'nationality',
        'phone',
        'created_by',
        'updated_by',
    ];
}
