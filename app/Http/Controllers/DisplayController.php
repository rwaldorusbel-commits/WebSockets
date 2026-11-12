<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class DisplayController extends Controller
{
    public function index()
    {
        $calls = Ticket::today()
            ->whereNotNull('called_at')
            ->with('counter')
            ->orderByDesc('called_at')
            ->take(6)
            ->get();

        return view('display', compact('calls'));
    }
}
