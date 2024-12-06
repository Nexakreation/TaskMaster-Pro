<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'date',
        'completed',
        'completed_at',
        'user_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'completed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 