<?php

namespace Tests\Feature;

use App\Mail\IntegrationTestMail;
use App\Models\Setting;
use App\Models\User;
use App\Services\SmtpConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SmtpIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'SMTP Admin',
            'email' => 'smtp-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_saved_database_smtp_settings_are_applied_to_the_laravel_mailer(): void
    {
        Setting::set('mail_host', 'smtp.example.org');
        Setting::set('mail_port', '465');
        Setting::set('mail_encryption', 'ssl');
        Setting::set('mail_username', 'mailer@example.org');
        Setting::set('mail_password', 'private-test-password');

        app(SmtpConfiguration::class)->applySavedSettings();

        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('smtp.example.org', config('mail.mailers.smtp.host'));
        $this->assertSame(465, config('mail.mailers.smtp.port'));
        $this->assertSame('ssl', config('mail.mailers.smtp.encryption'));
        $this->assertSame('mailer@example.org', config('mail.mailers.smtp.username'));
        $this->assertSame('private-test-password', config('mail.mailers.smtp.password'));
    }

    public function test_admin_can_send_a_test_email_only_to_their_own_address(): void
    {
        Mail::fake();
        Setting::set('mail_host', 'smtp.example.org');

        $this->actingAs($this->admin())
            ->post(route('admin.settings.smtp.test'))
            ->assertRedirect()
            ->assertSessionHas('success');

        Mail::assertSent(IntegrationTestMail::class, function (IntegrationTestMail $mail) {
            return $mail->hasTo('smtp-admin@example.org');
        });
    }

    public function test_test_email_requires_an_admin_and_saved_smtp_host(): void
    {
        $this->post(route('admin.settings.smtp.test'))
            ->assertRedirect(route('admin.login'));

        $this->actingAs($this->admin())
            ->post(route('admin.settings.smtp.test'))
            ->assertRedirect()
            ->assertSessionHas('error');
    }
}
