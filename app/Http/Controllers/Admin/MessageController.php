<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:Unread,Read,Resolved',
        ]);
        $status = $validated['status'] ?? 'Resolved';

        DB::transaction(function () use ($id, $status) {
            $msg = ContactMessage::whereKey($id)->lockForUpdate()->firstOrFail();
            $previousStatus = $msg->status;
            $msg->status = $status;
            $msg->save();

            AuditLog::log(
                'CONTACT_MESSAGE_STATUS_UPDATED',
                "Message #{$msg->id} status changed from {$previousStatus} to {$status}."
            );
        });

        return back()->with('success', 'Message status updated and audit logged.');
    }
}
