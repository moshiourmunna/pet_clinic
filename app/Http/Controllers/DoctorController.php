<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $speciality = $request->query('speciality');
        $day = $request->query('day'); // today|tomorrow

        $doctors = Doctor::active()->with('schedules')
            ->when($q !== '', fn ($w) => $w->where(fn ($x) => $x->where('name_en', 'like', "%{$q}%")->orWhere('name_bn', 'like', "%{$q}%")->orWhere('title_en', 'like', "%{$q}%")))
            ->when(array_key_exists((string) $speciality, Doctor::SPECIALITIES), fn ($w) => $w->where('speciality', $speciality))
            ->get();

        if (in_array($day, ['today', 'tomorrow'], true)) {
            $dow = ($day === 'today' ? today() : today()->addDay())->dayOfWeek;
            $doctors = $doctors->filter(fn ($d) => $d->worksOn($dow))->values();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'count' => $doctors->count(),
                'html' => view('doctors._grid', compact('doctors'))->render(),
            ]);
        }

        return view('doctors.index', compact('doctors'));
    }

    public function show(Doctor $doctor)
    {
        abort_unless($doctor->is_active, 404);
        $doctor->load('schedules');

        return view('doctors.show', compact('doctor'));
    }
}
