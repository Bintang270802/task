<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Project Model
 * 
 * Represents a project that can contain multiple tasks
 * 
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|Task[] $tasks
 * @property-read int $tasks_count
 */
class Project extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array<string>
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Get the tasks for the project
     *
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    
    /**
     * Get tasks ordered by priority
     *
     * @return HasMany
     */
    public function orderedTasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('priority');
    }
    
    /**
     * Check if project has any tasks
     *
     * @return bool
     */
    public function hasTasks(): bool
    {
        return $this->tasks()->exists();
    }
}
