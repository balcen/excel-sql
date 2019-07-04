<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'c_tax_id',
        'c_name',
        'c_type',
        'c_contact',
        'c_phone',
        'c_mail',
        'author',
        'file_name'
    ];
}
