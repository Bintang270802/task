<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            ['name' => 'Website Development'],
            ['name' => 'Mobile App'],
            ['name' => 'Marketing Campaign'],
        ];

        foreach ($projects as $project) {
            \App\Models\Project::create($project);
        }

        // Create sample tasks
        $tasks = [
            ['name' => 'Design homepage mockup', 'priority' => 1, 'project_id' => 1],
            ['name' => 'Setup Laravel project', 'priority' => 2, 'project_id' => 1],
            ['name' => 'Create database schema', 'priority' => 3, 'project_id' => 1],
            ['name' => 'Design app UI/UX', 'priority' => 4, 'project_id' => 2],
            ['name' => 'Setup React Native', 'priority' => 5, 'project_id' => 2],
            ['name' => 'Create social media content', 'priority' => 6, 'project_id' => 3],
        ];

        foreach ($tasks as $task) {
            \App\Models\Task::create($task);
        }
    }
}
