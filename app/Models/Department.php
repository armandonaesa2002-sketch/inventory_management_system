<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assignment;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    public function assignment()
    {
        $this->hasMany(Assignment::class);
    }
}
