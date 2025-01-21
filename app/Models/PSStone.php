<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PSStone extends Model
{
    protected $table = 'precious_semi_precious_stones';
    protected $fillable = [
        'name',
        'is_precious',
        'image_path',
        'photo',
        'latin_name',
        'quantity',
        'estimated_extraction',
        'estimated_price_from',
        'estimated_price_to',
        'offered_royality_by_private_sector',
        'final_royality_after_negotiations',
        'estimated_revenue_based_on_ORPS',
        'estimated_revenue_based_on_FRAN',
        'created_by',
    ];
}
