<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Services\BookingService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function create(Request $request)
    {
        $services = Service::active()->get();
        $doctors = Doctor::active()->get();

        return view('appointment.create', [
            'services' => $services,
            'doctors' => $doctors,
            'pets' => $request->user()?->pets ?? collect(),
            'selected' => [
                'service' => $services->firstWhere('slug', $request->query('service'))?->id ?? $request->query('service_id'),
                'doctor' => $doctors->firstWhere('slug', $request->query('doctor'))?->id ?? $doctors->first()?->id,
                'date' => $request->query('date', today()->addDay()->toDateString()),
                'pet_type' => $request->query('pet_type'),
                'phone' => $request->query('phone'),
                'time' => $request->query('time'),
            ],
        ]);
    }

    public function store(StoreAppointmentRequest $request, BookingService $booking)
    {
        $appointment = $booking->book($request->validated(), $request->user()?->id);

        $next = $appointment->payment_method !== 'clinic'
            ? route('payment.start', $appointment->code)
            : route('appointment.success', $appointment->code);

        if ($request->wantsJson()) {
            return response()->json(['code' => $appointment->code, 'redirect' => $next], 201);
        }

        return redirect($next);
    }

    public function show(string $code)
    {
        $appointment = Appointment::with('service', 'doctor')->where('code', $code)->firstOrFail();

        return view('appointment.success', compact('appointment'));
    }
}
