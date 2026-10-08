<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProjectTemplatesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Term $term;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Project Admin',
            'email' => 'project-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);

        $this->term = Term::create([
            'name' => '2026-2027',
            'start_date' => '2026-07-01',
            'end_date' => '2027-06-30',
            'is_active' => true,
        ]);
    }

    public function test_project_creation_page_shows_built_in_templates_and_terms(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.projects.index'));

        $response->assertOk();
        $response->assertSee('css/pages/admin-projects.css');
        $response->assertSee('js/pages/admin-projects.js');
        $response->assertSee('Start from a built-in template');
        $response->assertSee('Student Support');
        $response->assertSee('Career Readiness');
        $response->assertSee('2026-2027');
        $response->assertDontSee('View Details');
    }

    public function test_project_creation_saves_description_and_budget(): void
    {
        $description = 'Provide tutoring and learning materials to students.';

        $response = $this->actingAs($this->admin)->post(route('admin.projects.store'), [
            'term_id' => $this->term->id,
            'title' => 'Student Learning Support',
            'description' => $description,
            'budget' => '1250.50',
            'status' => 'Planned',
        ]);

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseHas('projects', [
            'term_id' => $this->term->id,
            'title' => 'Student Learning Support',
            'description' => $description,
            'budget' => '1250.50',
            'status' => 'Planned',
        ]);
        $this->assertSame('Student Learning Support', Project::firstOrFail()->title);
    }
}
