<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Faq;
use App\Models\Testimonial;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(['slug' => 'constitution-bylaws'], [
            'title' => 'KSO Chandigarh Constitution & Bylaws',
            'excerpt' => 'Official constitutional principles governing the student body in Chandigarh.',
            'content' => '<h3>ARTICLE I: NAME & JURISDICTION</h3><p>The name of the organisation shall be <strong>Kuki Students\' Organisation (KSO) Chandigarh</strong>. Its jurisdiction extends across all educational institutions in Chandigarh UT, Mohali, and Panchkula.</p><h3>ARTICLE II: AIMS & OBJECTIVES</h3><p>1. To promote academic excellence and guidance among student scholars.</p><p>2. To preserve and foster Kuki cultural heritage, language, and traditional arts.</p><p>3. To provide 24/7 welfare support for hostel, PG accommodation, and medical emergencies at PGIMER & GMCH-32.</p>',
            'meta_title' => 'KSO Chandigarh Constitution',
            'meta_description' => 'Constitutional principles and bylaws of KSO Chandigarh.',
            'is_published' => true,
        ]);

        Page::updateOrCreate(['slug' => 'freshers-guide-2026'], [
            'title' => 'Chandigarh Freshers\' Accommodation & Admission Guide',
            'excerpt' => 'Essential guidance for new students arriving in Chandigarh from Manipur.',
            'content' => '<h3>WELCOME TO CHANDIGARH!</h3><p>Arriving in a new city can be daunting. KSO Chandigarh has prepared this comprehensive handbook covering hostel form deadlines at Panjab University, top PG areas in Sector 15 and Sector 36, local bus connectivity (CTU), and safety guidelines.</p>',
            'meta_title' => 'Freshers Guide Chandigarh',
            'meta_description' => 'Guide for new students in Chandigarh.',
            'is_published' => true,
        ]);

        // FAQs
        Faq::updateOrCreate(['question' => 'How do I apply for KSO Chandigarh Student Membership?'], [
            'answer' => 'You can fill out the online Membership Registration form on our website. Upload your passport size photo, college ID proof, and contact details. Once reviewed, your official Digital Membership ID Card will be generated.',
            'category' => 'Membership',
            'sort_order' => 1
        ]);

        Faq::updateOrCreate(['question' => 'Who is eligible to become a member?'], [
            'answer' => 'Any student from the Kuki community or Manipur pursuing higher education in any university, college, or institute located in Chandigarh UT, Mohali, or Panchkula is eligible.',
            'category' => 'Membership',
            'sort_order' => 2
        ]);

        Faq::updateOrCreate(['question' => 'What emergency services does KSO Chandigarh offer?'], [
            'answer' => 'We operate a 24/7 Emergency Cell assisting students during medical emergencies at PGIMER or GMCH Sector 32, blood donation matching, legal aid guidance, and PG/hostel accommodation assistance.',
            'category' => 'Emergency Cell',
            'sort_order' => 3
        ]);

        // Testimonials
        Testimonial::updateOrCreate(['author_name' => 'Lalminthang Haokip'], [
            'author_title' => 'Alumni (MA Economics, Panjab University)',
            'college_name' => 'Panjab University Campus',
            'quote' => 'KSO Chandigarh was my home away from home during my 5 years at PU. From helping me secure hostel accommodation in my first year to career mentorship, KSO was always there.',
            'rating' => 5,
            'is_featured' => true
        ]);

        Testimonial::updateOrCreate(['author_name' => 'Chonghoithem Gangte'], [
            'author_title' => 'Alumni (BSc Biotechnology)',
            'college_name' => 'MCM DAV College Sector 36',
            'quote' => 'The Chavang Kut cultural nights and academic seminars organized by KSO Chandigarh gave us pride in our heritage and kept our community closely connected in the city.',
            'rating' => 5,
            'is_featured' => true
        ]);
    }
}
