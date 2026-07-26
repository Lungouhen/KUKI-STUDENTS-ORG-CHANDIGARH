<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Services\PaymentGatewayService;

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
            'payment_method' => 'nullable|string',
        ]);

        $donation = PaymentGatewayService::processSuccessfulDonation($validated);

        return redirect()->route('donations.index')->with('success', 'Thank you! Your donation of ₹' . number_format($donation->amount) . ' has been recorded and an official receipt has been issued.');
    }

    public function createRazorpayOrder(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'cause' => 'required|string',
            'donor_name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $orderPayload = PaymentGatewayService::createRazorpayOrder(
            $validated['amount'],
            $validated['cause'],
            $validated['donor_name'],
            $validated['email'] ?? '',
            $validated['phone'] ?? ''
        );

        return response()->json(['success' => true, 'order' => $orderPayload]);
    }
}
