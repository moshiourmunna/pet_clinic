<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $items = Appointment::with('service', 'doctor')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->doctor, fn ($q, $d) => $q->where('doctor_id', $d))
            ->when($request->date, fn ($q, $d) => $q->whereDate('date', $d))
            ->when($request->q, fn ($q, $s) => $q->where(fn ($x) => $x->where('code', 'like', "%$s%")->orWhere('phone', 'like', "%$s%")->orWhere('owner_name', 'like', "%$s%")))
            ->latest('date')->paginate(20)->withQueryString();

        return view('admin.appointments', ['items' => $items, 'doctors' => Doctor::orderBy('sort')->get()]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Appointment::STATUSES)],
            'payment_status' => ['nullable', Rule::in(['unpaid', 'paid'])],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);
        $update = ['status' => $data['status'], 'admin_note' => $data['admin_note'] ?? $appointment->admin_note];
        if (! empty($data['payment_status'])) $update['payment_status'] = $data['payment_status'];
        if (! empty($data['doctor_id'])) $update['doctor_id'] = $data['doctor_id'];
        if ($data['status'] === 'cancelled') $update['slot_key'] = null; // frees the slot
        $appointment->update($update);

        return back()->with('ok', 'Appointment '.$appointment->code.' updated.');
    }
}
