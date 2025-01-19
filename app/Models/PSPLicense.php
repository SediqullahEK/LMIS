<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PSPLicense extends Model
{
    protected $table = 'psp_licenses';

    protected $fillable = [
        'letter_id',
        'stone_id',
        'individual_id',
        'company_id',
        'stone_color_dr',
        'stone_color_en',
        'stone_amount',
        'serial_number',
        'issue_date',
        'expire_date',
        'is_valid',
        'signed_version_image_path',
    ];
}
