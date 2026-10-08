<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuditPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_trail_has_accessible_current_page_search_and_context(): void
    {
        $admin = User::create([
            'name' => 'Audit Page Admin',
            'email' => 'audit-page-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'UPDATE_SETTING',
            'ip_address' => '192.0.2.20',
            'user_agent' => 'Test Browser',
            'details' => 'Updated organization contact details',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.audit.index'))
            ->assertOk()
            ->assertSee('css/pages/admin-audit.css')
            ->assertSee('js/pages/admin-audit.js')
            ->assertSee('Filter audit entries by user, action, IP, or details')
            ->assertSee('UPDATE_SETTING')
            ->assertSee('192.0.2.20')
            ->assertSee('scope="col"', false);
    }
}
