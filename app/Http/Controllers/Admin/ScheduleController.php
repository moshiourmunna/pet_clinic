<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function edit(Doctor $doctor)
    {
        return view('admin.schedules', ['doctor' => $doctor->load('schedules')]);
    }

    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'rows' => ['array'],
            'rows.*.day_of_week' => ['required', 'integer', 'between:0,6'],
            'rows.*.start_time' => ['required', 'date_format:H:i'],
            'rows.*.end_time' => ['required', 'date_format:H:i', 'after:rows.*.start_time'],
            'rows.*.slot_minutes' => ['required', 'integer', 'between:5,240'],
        ]);
        DB::transaction(function () use ($doctor, $data) {
            $doctor->schedules()->delete();
            foreach ($data['rows'] ?? [] as $row) {
                $doctor->schedules()->create($row);
            }
        });

        return back()->with('ok', 'Schedule saved.');
    }
}
