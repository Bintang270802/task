<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test that home page redirects to tasks index.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/tasks');
    }

    /**
     * Test that tasks page loads successfully.
     */
    public function test_tasks_page_loads_successfully(): void
    {
        $response = $this->get('/tasks');

        $response->assertStatus(200);
    }

    /**
     * Test that projects page loads successfully.
     */
    public function test_projects_page_loads_successfully(): void
    {
        $response = $this->get('/projects');

        $response->assertStatus(200);
    }
}
