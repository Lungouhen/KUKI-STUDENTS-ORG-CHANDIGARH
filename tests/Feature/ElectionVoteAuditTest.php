<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Member;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ElectionVoteAuditTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Candidate $candidate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Election Admin',
            'email' => 'election-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);

        Member::create([
            'id' => 'KSO-CHD-2026-0010',
            'full_name' => 'Election Candidate',
            'gender' => 'Female',
            'phone' => '+91 90000 00000',
            'email' => 'election-candidate@example.org',
            'blood_group' => 'A+',
            'institution' => 'Test College',
            'course' => 'BCom',
            'year_of_study' => '1st Year',
            'permanent_address' => 'Manipur',
            'current_address' => 'Chandigarh',
            'emergency_contact' => 'Parent',
            'emergency_phone' => '+91 90000 00001',
            'status' => 'Approved',
        ]);

        $term = Term::create([
            'name' => '2026-2027',
            'start_date' => '2026-04-01',
            'end_date' => '2027-03-31',
            'is_active' => true,
        ]);

        $election = Election::create([
            'term_id' => $term->id,
            'position' => 'President',
            'election_date' => '2026-10-01',
            'status' => 'Completed',
        ]);

        $this->candidate = Candidate::create([
            'election_id' => $election->id,
            'member_id' => 'KSO-CHD-2026-0010',
            'votes_received' => 10,
        ]);
    }

    public function test_admin_election_list_has_accessible_schedule_form_and_scoped_assets(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.elections.index'))
            ->assertOk()
            ->assertSee('css/pages/admin-elections.css')
            ->assertSee('js/pages/admin-elections.js')
            ->assertSee('scope="col"', false)
            ->assertSee('for="electionTerm"', false)
            ->assertSee('Completed');
    }

    public function test_admin_vote_count_change_is_validated_and_audited(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.candidates.updateVotes', $this->candidate), ['votes' => 25])
            ->assertRedirect();

        $this->assertSame(25, $this->candidate->fresh()->votes_received);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->admin->id,
            'action' => 'ELECTION_VOTES_UPDATED',
        ]);
        $this->assertStringContainsString('10 to 25 votes', AuditLog::latest()->value('details'));
    }

    public function test_invalid_vote_count_does_not_change_candidate_or_create_audit_log(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.candidates.updateVotes', $this->candidate), ['votes' => '-1'])
            ->assertSessionHasErrors('votes');

        $this->assertSame(10, $this->candidate->fresh()->votes_received);
        $this->assertSame(0, AuditLog::count());
    }
}
