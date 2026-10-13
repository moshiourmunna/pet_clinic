<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'required_without:email', 'string', 'max:20'],
            'email' => ['nullable', 'required_without:phone', 'email', 'max:150'],
            'message' => ['required', 'string', 'min:5', 'max:2000'],
        ]);
        ContactMessage::create($data);

        return $request->wantsJson()
            ? response()->json(['message' => __('Thank you! Your message has been sent.')])
            : back()->with('ok', __('Thank you! Your message has been sent.'));
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:150']]);
        Subscriber::firstOrCreate($data);

        return $request->wantsJson()
            ? response()->json(['message' => __('Subscribed! Thank you.')])
            : back()->with('ok', __('Subscribed! Thank you.'));
    }
}
