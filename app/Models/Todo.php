<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Todo extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'completed'
    ];

    public $incrementing = false;
    protected $keyType = 'string';
}