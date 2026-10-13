<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale)
    {
        $request->session()->put('locale', $locale);
        if ($request->wantsJson()) {
            return response()->json(['locale' => $locale]);
        }

        return redirect()->to(url()->previous() ?: route('home'));
    }
}
