<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaoDich extends Model
{
    protected $table = 'tb_transactions';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'gateway',
        'transaction_date',
        'account_number',
        'sub_account',
        'amount_in',
        'amount_out',
        'accumulated',
        'code',
        'transaction_content',
        'reference_number',
        'body',
    ];
}
