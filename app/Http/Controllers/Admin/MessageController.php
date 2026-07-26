<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class MessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    public function updateStatus(Request $request, $id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->status = $request->input('status', 'Resolved');
        $msg->save();

        return back()->with('success', 'Message status updated.');
    }
}
