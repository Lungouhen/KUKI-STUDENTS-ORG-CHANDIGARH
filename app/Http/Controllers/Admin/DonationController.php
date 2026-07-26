<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::orderBy('date', 'desc')->paginate(15);
        $totalAmount = Donation::where('status', 'Completed')->sum('amount');

        return view('admin.donations.index', compact('donations', 'totalAmount'));
    }

    public function receipt($id)
    {
        $donation = Donation::findOrFail($id);
        return view('admin.donations.receipt', compact('donation'));
    }
}
