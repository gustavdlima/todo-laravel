<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'completed'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'due_date' => 'datetime',
        'status' => TaskStatus::class
    ];

    // query scopes
    public function scopePending($query)
    {
        return $query->where('status', TaskStatus::PENDING->value);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', TaskStatus::IN_PROGRESS->value);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', TaskStatus::COMPLETED->value);
    }
}
