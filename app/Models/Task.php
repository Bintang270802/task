<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Task Model
 * 
 * Represents a task in the task management system
 * 
 * @property int $id
 * @property string $name
 * @property int $priority
 * @property int|null $project_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Project|null $project
 */
class Task extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'priority',
        'project_id'
    ];

    /**
     * The attributes that should be cast
     *
     * @var array<string, string>
     */
    protected $casts = [
        'priority' => 'integer',
        'project_id' => 'integer'
    ];

    /**
     * Get the project that owns the task
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    
    /**
     * Scope a query to only include tasks with a specific project
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $projectId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }
    
    /**
     * Scope a query to order tasks by priority
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderedByPriority($query)
    {
        return $query->orderBy('priority');
    }
}
