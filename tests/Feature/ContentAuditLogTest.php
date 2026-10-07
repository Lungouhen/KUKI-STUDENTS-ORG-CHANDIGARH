<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ContentAuditLogTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'CMS Administrator',
            'email' => 'content-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_news_create_update_and_delete_are_audited_with_actor_and_context(): void
    {
        $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => 'Student notice',
            'category' => 'Notice',
            'content' => 'Initial notice text',
            'author' => 'Student Desk',
        ])->assertRedirect();

        $news = News::firstOrFail();
        $this->assertAudit('CREATE_NEWS', $news->id, $this->admin->id, 'Student notice');

        $this->put(route('admin.news.update', $news->id), [
            'title' => 'Updated student notice',
            'category' => 'Academic',
            'content' => 'Updated notice text',
            'author' => 'Academic Desk',
        ])->assertRedirect(route('admin.news.index'));
        $this->assertAudit('UPDATE_NEWS', $news->id, $this->admin->id, 'Updated student notice');

        $this->delete(route('admin.news.destroy', $news->id))->assertRedirect();
        $this->assertAudit('DELETE_NEWS', $news->id, $this->admin->id, 'Updated student notice');
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    public function test_faq_create_update_and_delete_are_audited_with_actor_and_context(): void
    {
        $this->actingAs($this->admin)->post(route('admin.faqs.store'), [
            'question' => 'How do I join?',
            'answer' => 'Submit the membership form.',
            'category' => 'Membership',
        ])->assertRedirect();

        $faq = Faq::firstOrFail();
        $this->assertAudit('CREATE_FAQ', $faq->id, $this->admin->id, 'How do I join?');

        $this->put(route('admin.faqs.update', $faq->id), [
            'question' => 'How can I join?',
            'answer' => 'Complete the student membership form.',
            'category' => 'Membership',
        ])->assertRedirect(route('admin.faqs.index'));
        $this->assertAudit('UPDATE_FAQ', $faq->id, $this->admin->id, 'How can I join?');

        $this->delete(route('admin.faqs.destroy', $faq->id))->assertRedirect();
        $this->assertAudit('DELETE_FAQ', $faq->id, $this->admin->id, 'How can I join?');
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    private function assertAudit(string $action, int $recordId, int $userId, string $context): void
    {
        $entity = str_ends_with($action, '_FAQ') ? 'FAQ' : 'News';
        $field = $entity === 'FAQ' ? 'question' : 'title';

        $this->assertDatabaseHas('audit_logs', [
            'action' => $action,
            'user_id' => $userId,
            'details' => "{$entity} ID: {$recordId}, {$field}: {$context}",
        ]);
    }
}
