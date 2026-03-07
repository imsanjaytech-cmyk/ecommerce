<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Save email logic here

        return response()->json([
            'success' => true,
            'message' => 'Subscribed successfully!'
        ]);
    }
}