<?php

namespace App\Models;

use App\Enums\StatusType;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['name'];

    protected $casts = [
        'name' => StatusType::class
    ];
}
