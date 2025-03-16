<?php

namespace App\Infrastructure\Models;

use App\Enums\TaskStatus;
use App\Model\User;
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
        'user_id',
        'completed'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'due_date' => 'datetime',
        'status' => TaskStatus::class
    ];

    public function user()
    {
        return $this->belongsTo(User::Class);
    }

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

    protected static function newFactory()
    {
        return \Database\Factories\TaskFactory::new();
    }
}
