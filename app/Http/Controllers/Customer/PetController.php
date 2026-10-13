<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\PetRequest;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PetController extends Controller
{
    public function index(Request $r)
    {
        return view('dashboard.pets.index', ['pets' => $r->user()->pets()->withCount('appointments')->get()]);
    }

    public function create()
    {
        return view('dashboard.pets.form', ['pet' => new Pet()]);
    }

    public function store(PetRequest $r)
    {
        $r->user()->pets()->create($r->validated());

        return redirect()->route('dashboard.pets.index')->with('ok', __('Saved.'));
    }

    public function edit(Pet $pet)
    {
        Gate::authorize('update', $pet);

        return view('dashboard.pets.form', compact('pet'));
    }

    public function update(PetRequest $r, Pet $pet)
    {
        Gate::authorize('update', $pet);
        $pet->update($r->validated());

        return redirect()->route('dashboard.pets.index')->with('ok', __('Saved.'));
    }

    public function destroy(Pet $pet)
    {
        Gate::authorize('delete', $pet);
        $pet->delete();

        return back()->with('ok', __('Saved.'));
    }
}
