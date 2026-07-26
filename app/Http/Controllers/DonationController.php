<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::where('status', 'Completed')->orderBy('date', 'desc')->take(10)->get();
        return view('donations.index', compact('donations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'cause' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'payment_ref' => 'required|string',
        ]);

        Donation::create([
            'donor_name' => $validated['donor_name'],
            'amount' => $validated['amount'],
            'cause' => $validated['cause'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'payment_ref' => $validated['payment_ref'],
            'status' => 'Completed',
            'date' => now()->toDateString(),
        ]);

        return redirect()->route('donations.index')->with('success', 'Thank you! Your donation record has been submitted.');
    }
}
