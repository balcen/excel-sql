<?php

namespace App\Repositories\FIle;

use App\Entities\Order;
use App\Entities\Client;
use App\Entities\Product;
use App\Entities\Invoice;
use Illuminate\Database\Eloquent\Model;

class Header
{
    public static function apply(Model $model)
    {
        switch (1) {
            case $model instanceof Client:
                return ['c_tax_id', 'c_name', 'c_type', 'c_contact', 'c_phone', 'c_mail'];
            case $model instanceof Product:
                return ['p_type', 'p_name', 'p_part_no', 'p_spec', 'p_size', 'p_weight', 'p_price', 'p_note'];
            case $model instanceof Order:
                return ['o_no', 'o_date', 'o_seller_name', 'o_buyer_name', 'o_product_name', 'o_product_part_no', 'o_product_spec', 'o_product_price', 'o_currency', 'o_quantity', 'o_amount', 'o_note'];
            case $model instanceof Invoice:
                return ['i_no', 'i_date', 'i_mature', 'i_order_no', 'i_seller_name', 'i_buyer_name', 'i_product_name', 'i_product_part_no', 'i_product_spec', 'i_product_price', 'i_currency', 'i_quantity', 'i_amount', 'i_note'];
        }
    }
}
