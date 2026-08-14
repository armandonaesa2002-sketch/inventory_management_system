<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    
    protected $fillable = [
        'item_type',
        'item_name',
        'quantity',
        'item_description'
    ];
}
