<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionVote;
use App\Models\Member;
use App\Models\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberPortalTest extends TestCase
{
    use RefreshDatabase;

    private Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = $this->makeMember('KSO-CHD-2026-0001', 'portal-member@kso.org');
    }

    private function makeMember(string $id, string $email, array $overrides = []): Member
    {
        return Member::create(array_merge([
            'id' => $id,
            'full_name' => 'Portal Member',
            'gender' => 'Male',
            'dob' => '2003-05-15',
            'phone' => '+91 90000 00000',
            'email' => $email,
            'blood_group' => 'B+',
            'institution' => 'Panjab University',
            'course' => 'BSc',
            'year_of_study' => '2nd Year',
            'permanent_address' => 'Manipur',
            'current_address' => 'Chandigarh',
            'emergency_contact' => 'Parent',
            'emergency_phone' => '+91 90000 11111',
            'status' => 'Approved',
        ], $overrides));
    }

    private function loginAsMember(): void
    {
        $this->withSession(['member_id' => $this->member->id]);
    }

    public function test_portal_login_page_has_labeled_fields_and_scoped_assets(): void
    {
        $this->get(route('membership.portal'))
            ->assertOk()
            ->assertSee('css/pages/member-login.css')
            ->assertSee('js/pages/member-login.js')
            ->assertSee('for="member-identifier"', false)
            ->assertSee('for="member-dob"', false)
            ->assertSee('id="member-login-status"', false);
    }

    private function makeOngoingElection(): array
    {
        $term = Term::create([
            'name' => '2026-2027',
            'start_date' => '2026-07-01',
            'end_date' => '2027-06-30',
            'is_active' => true,
        ]);

        $election = Election::create([
            'term_id' => $term->id,
            'position' => 'President',
            'election_date' => now()->format('Y-m-d'),
            'status' => 'Ongoing',
        ]);

        $candidateMember = $this->makeMember('KSO-CHD-2026-0002', 'candidate@kso.org');
        $candidate = Candidate::create([
            'election_id' => $election->id,
            'member_id' => $candidateMember->id,
            'votes_received' => 0,
        ]);

        return [$election, $candidate];
    }

    public function test_login_requires_matching_dob(): void
    {
        $response = $this->post('/members/portal/login', [
            'identifier' => $this->member->id,
            'dob' => '1999-01-01',
        ]);

        $response->assertRedirect();
        $response->assertSessionMissing('member_id');
    }

    public function test_login_succeeds_with_identifier_and_dob(): void
    {
        $response = $this->post('/members/portal/login', [
            'identifier' => $this->member->id,
            'dob' => '2003-05-15',
        ]);

        $response->assertRedirect(route('membership.portalDashboard'));
        $response->assertSessionHas('member_id', $this->member->id);
    }

    public function test_login_without_dob_is_rejected(): void
    {
        $response = $this->post('/members/portal/login', [
            'identifier' => $this->member->id,
        ]);

        $response->assertSessionHasErrors('dob');
        $response->assertSessionMissing('member_id');
    }

    public function test_logout_clears_session_and_redirects_to_login_page(): void
    {
        $this->loginAsMember();

        $response = $this->get('/members/portal/logout');

        $response->assertRedirect(route('membership.portal'));
        $response->assertSessionMissing('member_id');

        $this->get(route('membership.portal'))->assertOk();
    }

    public function test_member_can_vote_once_in_ongoing_election(): void
    {
        [$election, $candidate] = $this->makeOngoingElection();
        $this->loginAsMember();

        $response = $this->post('/members/portal/vote', [
            'candidate_id' => $candidate->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertSame(1, $candidate->fresh()->votes_received);
        $this->assertDatabaseHas('election_votes', [
            'election_id' => $election->id,
            'member_id' => $this->member->id,
        ]);
    }

    public function test_member_cannot_vote_twice_in_same_election(): void
    {
        [$election, $candidate] = $this->makeOngoingElection();
        $this->loginAsMember();

        ElectionVote::create([
            'election_id' => $election->id,
            'member_id' => $this->member->id,
        ]);

        $response = $this->post('/members/portal/vote', [
            'candidate_id' => $candidate->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertSame(0, $candidate->fresh()->votes_received);
        $this->assertSame(1, ElectionVote::where('election_id', $election->id)->count());
    }

    public function test_votes_rejected_when_election_not_ongoing(): void
    {
        [$election, $candidate] = $this->makeOngoingElection();
        $election->update(['status' => 'Completed']);
        $this->loginAsMember();

        $response = $this->post('/members/portal/vote', [
            'candidate_id' => $candidate->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertSame(0, $candidate->fresh()->votes_received);
        $this->assertDatabaseMissing('election_votes', [
            'election_id' => $election->id,
            'member_id' => $this->member->id,
        ]);
    }

    public function test_guest_cannot_vote(): void
    {
        [, $candidate] = $this->makeOngoingElection();

        $response = $this->post('/members/portal/vote', [
            'candidate_id' => $candidate->id,
        ]);

        $response->assertRedirect(route('membership.portal'));
        $this->assertSame(0, $candidate->fresh()->votes_received);
    }

    public function test_dashboard_shows_real_fee_data_and_open_elections(): void
    {
        [$election] = $this->makeOngoingElection();
        $this->member->feePayments()->create([
            'period' => '2026-27',
            'amount' => 250,
            'payment_method' => 'Cash',
            'voucher_no' => 'VOUCH-2026-TEST01',
            'paid_on' => '2026-08-01',
        ]);
        $this->loginAsMember();

        $response = $this->get('/members/portal/dashboard');

        $response->assertOk();
        $response->assertSee('css/pages/member-dashboard.css')
            ->assertSee('js/pages/member-dashboard.js')
            ->assertSee('role="tablist"', false)
            ->assertSee('role="tabpanel"', false)
            ->assertSee('id="dashboard-tab-overview"', false)
            ->assertSee('id="printMemberIdCard"', false)
            ->assertSee('role="radiogroup"', false);
        $response->assertViewHas('totalFeesPaid', 250.0);
        $response->assertViewHas('paymentsCount', 1);
        $response->assertViewHas('openElections', fn ($elections) => $elections->contains('id', $election->id));
        $response->assertViewHas('votedElectionIds', []);
    }
}
