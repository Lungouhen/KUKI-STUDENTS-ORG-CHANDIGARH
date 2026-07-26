<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'id' => 'KSO-CHD-2026-0001',
                'full_name' => 'Seinthang Haokip',
                'gender' => 'Male',
                'dob' => '2003-05-14',
                'phone' => '+91 98765 11001',
                'email' => 'seinthang.h@gmail.com',
                'blood_group' => 'O+',
                'institution' => 'Panjab University, Sector 14',
                'course' => 'MA Political Science',
                'department' => 'Department of Political Science',
                'year_of_study' => '2nd Year',
                'roll_no' => 'PU2024-POL-042',
                'permanent_address' => 'Tuibong, Churachandpur, Manipur - 795128',
                'current_address' => 'Hostel No. 4, Panjab University Campus, Chandigarh',
                'emergency_contact' => 'Paotinthang Haokip (Father)',
                'emergency_phone' => '+91 94360 99881',
                'photo' => '/images/default-avatar-m.png',
                'status' => 'Approved',
                'membership_type' => 'Regular Student Member',
                'applied_date' => '2026-07-01',
                'approval_date' => '2026-07-02',
                'valid_until' => '2027-06-30'
            ],
            [
                'id' => 'KSO-CHD-2026-0002',
                'full_name' => 'Hoineiching Chongloi',
                'gender' => 'Female',
                'dob' => '2004-09-20',
                'phone' => '+91 98765 11002',
                'email' => 'hoinei.c@gmail.com',
                'blood_group' => 'B+',
                'institution' => 'MCM DAV College for Women, Sector 36',
                'course' => 'BSc Biotechnology',
                'department' => 'Biotechnology',
                'year_of_study' => '3rd Year',
                'roll_no' => 'MCM-2023-BIO-018',
                'permanent_address' => 'Motbung, Kangpokpi District, Manipur - 795107',
                'current_address' => 'House No. 1210, Sector 36-B, Chandigarh',
                'emergency_contact' => 'Lalboi Chongloi (Brother)',
                'emergency_phone' => '+91 94360 88223',
                'photo' => '/images/default-avatar-f.png',
                'status' => 'Approved',
                'membership_type' => 'Regular Student Member',
                'applied_date' => '2026-07-03',
                'approval_date' => '2026-07-04',
                'valid_until' => '2027-06-30'
            ],
            [
                'id' => 'KSO-CHD-2026-0003',
                'full_name' => 'Paominlun Kipgen',
                'gender' => 'Male',
                'dob' => '2002-12-05',
                'phone' => '+91 98765 11003',
                'email' => 'paominlun.k@gmail.com',
                'blood_group' => 'A+',
                'institution' => 'DAV College, Sector 10',
                'course' => 'BCA (Bachelor of Computer Applications)',
                'department' => 'Computer Science & IT',
                'year_of_study' => '3rd Year',
                'roll_no' => 'DAV10-2023-BCA-109',
                'permanent_address' => 'Keithelmanbi, Kangpokpi, Manipur - 795107',
                'current_address' => 'Room 14, PG Sector 15-D, Chandigarh',
                'emergency_contact' => 'Thangjang Kipgen (Father)',
                'emergency_phone' => '+91 94362 11445',
                'photo' => '/images/default-avatar-m.png',
                'status' => 'Approved',
                'membership_type' => 'Executive Member',
                'applied_date' => '2026-06-15',
                'approval_date' => '2026-06-16',
                'valid_until' => '2027-06-30'
            ],
            [
                'id' => 'KSO-CHD-2026-0004',
                'full_name' => 'Veineiting Lhungdim',
                'gender' => 'Female',
                'dob' => '2005-03-18',
                'phone' => '+91 98765 11004',
                'email' => 'veinei.l@gmail.com',
                'blood_group' => 'O+',
                'institution' => 'Post Graduate Govt College Sector 11',
                'course' => 'BA English Honours',
                'department' => 'Department of English',
                'year_of_study' => '1st Year',
                'roll_no' => 'PGGC11-2026-BA-055',
                'permanent_address' => 'Saikul, Kangpokpi, Manipur - 795138',
                'current_address' => 'Sector 15-C, Chandigarh',
                'emergency_contact' => 'Hentinlhing Lhungdim (Mother)',
                'emergency_phone' => '+91 98620 55412',
                'photo' => '/images/default-avatar-f.png',
                'status' => 'Pending',
                'membership_type' => 'Regular Student Member',
                'applied_date' => '2026-07-25',
                'approval_date' => null,
                'valid_until' => '2027-06-30'
            ]
        ];

        foreach ($members as $m) {
            Member::updateOrCreate(['id' => $m['id']], $m);
        }
    }
}
