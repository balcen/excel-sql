<?php

namespace App\Entities;


class Client extends Model
{
    public $timestamps = true;

    protected $table = 'clients';

    protected $fillable = [
        'c_tax_id',
        'c_name',
        'c_type',
        'c_contact',
        'c_phone',
        'c_mail',
        'user_id',
        'file_name'
    ];
}
