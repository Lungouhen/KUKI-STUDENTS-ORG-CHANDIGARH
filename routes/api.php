<?php

use Illuminate\Support\Facades\Route;
use App\Models\Member;

Route::get('/verify/{id}', function ($id) {
    $member = Member::query()
        ->select([
            'id',
            'full_name',
            'status',
            'institution',
            'course',
            'year_of_study',
            'membership_type',
            'valid_until',
            'photo',
        ])
        ->find($id);

    if (!$member) {
        return response()->json(['success' => false, 'message' => 'Member ID not found'], 404);
    }

    return response()->json(['success' => true, 'member' => $member]);
})->middleware('throttle:10,1');
