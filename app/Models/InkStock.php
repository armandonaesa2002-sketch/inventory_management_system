<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InkStock extends Model
{
    protected $table = 'ink_stock';

    protected $fillable = [
        'brand',
        'type',
        'color',
        'stock',
        'reorder_level',
        'status'
    ];
}
