<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;

class Product extends Model
{
    use HasUpsertQueries;

    protected $table = 'products';

    protected $fillable = [
        'p_type',
        'p_image',
        'p_name',
        'p_part_no',
        'p_spec',
        'p_price',
        'p_currency',
        'p_size',
        'p_weight',
        'p_note',
        'author',
        'file_name'
    ];

    protected $casts = [
        'p_price' => 'double'
    ];
}
