<?php

namespace App\Entities;


class Product extends Model
{
    public $timestamps = true;

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
