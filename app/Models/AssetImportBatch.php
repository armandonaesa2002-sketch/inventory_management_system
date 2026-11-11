<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;
use App\Models\User;

class AssetImportBatch extends Model
{
    protected $table = 'asset_import_batches';

    protected $fillable = [
        'batch_code',
        'file_name',
        'imported_by',
        'total_success',
        'total_failed',
        'imported_at',
        'reverted_at',
        'completed_at',
    ];

    protected $casts = [
        'imported_at' => 'datetime',
        'reverted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'import_batch_id');
    }
    public function users(){
        return $this->belongsTo(User::class, 'imported_by');
    }
}
