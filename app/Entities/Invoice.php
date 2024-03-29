<?php

namespace App\Entities;


class Invoice extends Model
{
    public $timestamps = true;

    protected $table = 'invoices';

    protected $fillable = [
        'i_no',
        'i_date',
        'i_mature',
        'i_order_no',
        'i_seller_name',
        'i_buyer_name',
        'i_product_name',
        'i_product_part_no',
        'i_product_spec',
        'i_product_price',
        'i_currency',
        'i_quantity',
        'i_amount',
        'i_note',
        'user_id',
        'file_name'
    ];

    protected $casts = [
        'i_product_price' => 'double',
        'i_amount' => 'double'
    ];
}
