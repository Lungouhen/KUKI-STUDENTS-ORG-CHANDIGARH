<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSettingsSecurityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Settings Admin',
            'email' => 'settings-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_only_settings_form_fields_are_persisted_and_secret_updates_are_audited_without_values(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.settings.update'), [
                'siteName' => 'Updated KSO',
                'razorpaySecret' => 'new-secret-value',
                'openaiKey' => 'unlisted-secret',
                'unexpectedSetting' => 'untrusted-value',
            ])
            ->assertRedirect();

        $this->assertSame('Updated KSO', Setting::get('siteName'));
        $this->assertSame('new-secret-value', Setting::get('razorpaySecret'));
        $this->assertNull(Setting::get('openaiKey'));
        $this->assertNull(Setting::get('unexpectedSetting'));
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->admin->id,
            'action' => 'UPDATE_SETTINGS',
            'details' => json_encode(['updated_keys' => ['siteName', 'razorpaySecret']]),
        ]);
        $this->assertStringNotContainsString('new-secret-value', AuditLog::latest()->value('details'));
    }

    public function test_blank_secret_inputs_preserve_existing_credentials(): void
    {
        Setting::set('razorpaySecret', 'stored-razorpay-secret');
        Setting::set('mail_password', 'stored-smtp-password');

        $this->actingAs($this->admin)
            ->post(route('admin.settings.update'), [
                'razorpaySecret' => '',
                'mail_password' => '',
            ])
            ->assertRedirect();

        $this->assertSame('stored-razorpay-secret', Setting::get('razorpaySecret'));
        $this->assertSame('stored-smtp-password', Setting::get('mail_password'));
    }

    public function test_settings_pages_never_render_stored_secrets(): void
    {
        Setting::set('razorpaySecret', 'stored-razorpay-secret');
        Setting::set('mail_password', 'stored-smtp-password');

        $this->actingAs($this->admin)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertDontSee('stored-razorpay-secret')
            ->assertSee('Configured; enter a new secret to replace');

        $this->get(route('admin.settings.gateways'))
            ->assertOk()
            ->assertDontSee('stored-razorpay-secret');

        $this->get(route('admin.settings.smtp'))
            ->assertOk()
            ->assertDontSee('stored-smtp-password');
    }

    public function test_invalid_setting_values_are_rejected_without_writes(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.settings.update'), [
                'primaryColor' => 'not-a-color',
                'mail_port' => '99999',
            ])
            ->assertSessionHasErrors(['primaryColor', 'mail_port']);

        $this->assertNull(Setting::get('primaryColor'));
        $this->assertNull(Setting::get('mail_port'));
        $this->assertSame(0, AuditLog::count());
    }
}
