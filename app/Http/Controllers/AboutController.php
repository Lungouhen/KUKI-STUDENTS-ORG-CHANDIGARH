<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommitteeMember;

class AboutController extends Controller
{
    public function index()
    {
        $committee = CommitteeMember::orderBy('display_order')->get();
        return view('about', compact('committee'));
    }
}
