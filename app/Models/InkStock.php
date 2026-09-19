<?php

namespace App\Models;

use App\Models\InkTransaction;

use Illuminate\Database\Eloquent\Model;

class InkStock extends Model
{
    protected $table = 'ink_stocks';

    protected $fillable = [
        'brand',
        'type',
        'color',
        'stock',
        'reorder_level',
        'status',
        'in',
        'out'
    ];
    public function transactions()
    {
        return $this->hasMany(InkTransaction::class);
    }
}
