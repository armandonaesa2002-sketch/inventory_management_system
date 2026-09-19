<?php

namespace App\Models;

use App\Models\InkStock;

use Illuminate\Database\Eloquent\Model;

class InkTransaction extends Model
{
    protected $table = 'ink_transactions';

    protected $fillable = [
        'ink_stock_id',
        'type',
        'quantity',
        'transaction_date',
        'received_by',
        'released_to',
        'remarks'
    ];

    public function inkstock()
    {
        return $this->belongsTo(InkStock::class);
    }
}
