<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Display;
use Illuminate\View\View;

class DisplayController extends Controller
{
    public function show(string $uniqueCode): View
    {
        $display = Display::where('unique_code', $uniqueCode)->firstOrFail();

        $display->touchLastSeen();

        return view('public.display', ['display' => $display]);
    }
}
