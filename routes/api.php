<?php

use Illuminate\Support\Facades\Route;
use App\Models\Member;

Route::get('/verify/{id}', function ($id) {
    $member = Member::find($id);
    if (!$member) {
        return response()->json(['success' => false, 'message' => 'Member ID not found'], 404);
    }
    return response()->json(['success' => true, 'member' => $member]);
});
