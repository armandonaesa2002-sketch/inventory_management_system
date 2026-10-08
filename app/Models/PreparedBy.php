<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparedBy extends Model
{
    protected $table = 'prepared_by_options';
    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];
}
