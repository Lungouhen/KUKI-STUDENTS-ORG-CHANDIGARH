<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MemberCustomField;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberCustomFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_member_custom_fields_and_save_values(): void
    {
        $admin = User::create([
            'name' => 'Field Admin',
            'email' => 'field-admin@example.org',
            'password' => Hash::make('test password'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.memberCustomFields.store'), [
                'label' => 'Caste Category',
                'field_type' => 'select',
                'options' => "General\nOBC\nSC\nST",
                'is_required' => true,
                'sort_order' => 1,
            ])
            ->assertRedirect(route('admin.memberCustomFields.index'));

        $field = MemberCustomField::query()->firstOrFail();

        $this->assertDatabaseHas('member_custom_fields', [
            'slug' => 'caste-category',
            'field_type' => 'select',
            'is_required' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.members.create'))
            ->assertOk()
            ->assertSee('Caste Category');

        $this->actingAs($admin)
            ->post(route('admin.members.store'), [
                'full_name' => 'Custom Field Member',
                'gender' => 'Male',
                'dob' => '2004-01-01',
                'phone' => '+91 98765 00000',
                'email' => 'customfield@example.com',
                'blood_group' => 'O+',
                'institution' => 'Panjab University, Sector 14',
                'course' => 'BSc',
                'year_of_study' => '2nd Year',
                'permanent_address' => 'Churachandpur, Manipur',
                'current_address' => 'Sector 15, Chandigarh',
                'emergency_contact' => 'Father Name',
                'emergency_phone' => '+91 98765 11111',
                'status' => 'Approved',
                'custom_fields' => [
                    'caste-category' => 'OBC',
                ],
            ])->assertRedirect(route('admin.members.index'));

        $member = Member::query()->where('email', 'customfield@example.com')->firstOrFail();

        $this->assertDatabaseHas('member_custom_field_values', [
            'member_id' => $member->id,
            'field_id' => $field->id,
            'value' => 'OBC',
        ]);
    }
}
