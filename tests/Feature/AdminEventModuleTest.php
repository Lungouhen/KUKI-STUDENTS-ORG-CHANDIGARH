<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminEventModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Event Admin',
            'email' => 'event-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_event_register_has_scoped_assets_and_non_nested_bulk_controls(): void
    {
        $event = $this->event();

        $response = $this->actingAs($this->admin)->get(route('admin.events.index'));
        $response->assertOk()
            ->assertSee('css/pages/admin-events.css')
            ->assertSee('js/pages/admin-events.js')
            ->assertSee($event->title)
            ->assertSee('form="eventsBulkForm"', false)
            ->assertSee(route('admin.events.edit', $event->id));

        $html = $response->getContent();
        $bulkFormEnd = strpos($html, '</form>', strpos($html, 'id="eventsBulkForm"'));
        $eventsTable = strpos($html, '<table', strpos($html, 'id="eventsBulkForm"'));
        $this->assertNotFalse($bulkFormEnd);
        $this->assertNotFalse($eventsTable);
        $this->assertLessThan($eventsTable, $bulkFormEnd);
    }

    public function test_create_validation_errors_preserve_event_fields(): void
    {
        $this->actingAs($this->admin)
            ->from(route('admin.events.index'))
            ->post(route('admin.events.store'), [
                'title' => 'Event needing correction',
                'category' => 'Academic',
                'date' => 'not-a-date',
                'time' => 'Morning',
                'venue' => 'Campus',
                'description' => 'Event description.',
                'status' => 'Upcoming',
                'publication_status' => 'published',
            ])
            ->assertRedirect(route('admin.events.index'))
            ->assertSessionHasErrors('date');

        $this->get(route('admin.events.index'))
            ->assertOk()
            ->assertSee('Event needing correction')
            ->assertSee('aria-labelledby="eventErrorsHeading"', false);
    }

    public function test_edit_screen_is_accessible_and_scopes_assets_to_the_events_module(): void
    {
        $event = $this->event();

        $this->actingAs($this->admin)
            ->get(route('admin.events.edit', $event->id))
            ->assertOk()
            ->assertSee('css/pages/admin-events.css')
            ->assertSee('js/pages/admin-events.js')
            ->assertSee('for="eventTitle"', false)
            ->assertSee($event->title);
    }

    private function event(): Event
    {
        return Event::create([
            'title' => 'Campus community event',
            'category' => 'Academic',
            'date' => now()->addWeek()->toDateString(),
            'time' => '10:00 AM',
            'venue' => 'Campus Hall',
            'description' => 'A student event.',
            'status' => 'Upcoming',
            'publication_status' => 'published',
        ]);
    }
}
