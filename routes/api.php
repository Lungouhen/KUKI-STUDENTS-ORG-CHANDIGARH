<?php

use Illuminate\Support\Facades\Route;
use App\Models\Member;

/*
|--------------------------------------------------------------------------
| API Routes — KSO Chandigarh
|--------------------------------------------------------------------------
*/

/**
 * Public membership verification (scanned from the ID-card QR code).
 *
 * This endpoint is unauthenticated, so it deliberately exposes only what an
 * institution needs to confirm a card is genuine. It must never return the
 * full model: that would leak DOB, phone, email, home address and emergency
 * contacts to anyone who can guess a sequential membership ID.
 */
Route::get('/verify/{id}', function (string $id) {
    $member = Member::find($id);

    if (! $member) {
        return response()->json([
            'success' => false,
            'message' => 'Member ID not found',
        ], 404);
    }

    $isValid = $member->status === 'Approved'
        && (! $member->valid_until || ! $member->valid_until->isPast());

    return response()->json([
        'success' => true,
        'member' => [
            'membership_id' => $member->id,
            'full_name' => $member->full_name,
            'institution' => $member->institution,
            'membership_type' => $member->membership_type,
            'status' => $member->status,
            'valid_until' => $member->valid_until?->toDateString(),
            'is_valid' => $isValid,
        ],
    ]);
})->name('api.member.verify');
