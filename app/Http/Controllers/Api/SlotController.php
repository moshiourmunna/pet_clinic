<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Services\SlotService;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function __invoke(Request $request, Doctor $doctor, SlotService $slots)
    {
        $data = $request->validate(['date' => ['required', 'date_format:Y-m-d']]);

        return response()->json([
            'doctor' => ['id' => $doctor->id, 'name' => $doctor->name, 'fee' => $doctor->fee],
            'date' => $data['date'],
            'slots' => $slots->forDate($doctor, $data['date']),
        ]);
    }
}
