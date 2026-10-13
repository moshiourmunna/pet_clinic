<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Vaccination;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    private function petIds(Request $r) { return $r->user()->pets()->pluck('id'); }

    /** Appointments that belong to the user (by account, or booked as guest with the same phone). */
    private function mine(Request $r)
    {
        $u = $r->user();
        return Appointment::with('service', 'doctor')->where(fn ($q) => $q->where('user_id', $u->id)->when($u->phone, fn ($x) => $x->orWhere('phone', $u->phone)));
    }

    public function index(Request $r)
    {
        $pets = $this->petIds($r);
        return view('dashboard.index', [
            'stats' => [
                'upcoming' => (clone $this->mine($r))->whereIn('status', ['requested', 'confirmed'])->whereDate('date', '>=', today())->count(),
                'pets' => $pets->count(),
                'vaccine_due' => Vaccination::whereIn('pet_id', $pets)->whereBetween('due_on', [today()->subMonths(6), today()->addDays(30)])->count(),
                'paid' => $r->user()->payments()->where('status', 'paid')->sum('amount'),
            ],
            'appointments' => $this->mine($r)->latest('date')->take(5)->get(),
        ]);
    }

    public function appointments(Request $r)
    {
        return view('dashboard.appointments', ['appointments' => $this->mine($r)->latest('date')->paginate(10)]);
    }

    public function cancel(Request $r, Appointment $appointment)
    {
        $this->authorizeOwn($r, $appointment);
        $appointment->cancel();

        return back()->with('ok', __('Appointment cancelled.'));
    }

    public function reschedule(Request $r, Appointment $appointment, BookingService $booking)
    {
        $this->authorizeOwn($r, $appointment);
        $data = $r->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => [Rule::requiredIf($appointment->type === 'slot'), 'nullable', 'date_format:H:i'],
        ]);
        $booking->reschedule($appointment, $data['date'], $data['time'] ?? null);

        return back()->with('ok', __('Appointment rescheduled.'));
    }

    public function records(Request $r)
    {
        return view('dashboard.records', ['records' => MedicalRecord::with('pet', 'doctor')->whereIn('pet_id', $this->petIds($r))->latest('visit_date')->paginate(10)]);
    }

    public function vaccinations(Request $r)
    {
        return view('dashboard.vaccinations', ['vaccinations' => Vaccination::with('pet')->whereIn('pet_id', $this->petIds($r))->orderBy('due_on')->paginate(15)]);
    }

    public function payments(Request $r)
    {
        return view('dashboard.payments', ['payments' => $r->user()->payments()->with('appointment.service')->latest()->paginate(10)]);
    }

    public function settings(Request $r)
    {
        return view('dashboard.settings', ['user' => $r->user()]);
    }

    public function updateSettings(Request $r)
    {
        $u = $r->user();
        $data = $r->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($u->id)],
            'phone' => ['required', 'regex:/^(\+?880|0)1[3-9]\d{8}$/', Rule::unique('users')->ignore($u->id)],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);
        if (empty($data['password'])) unset($data['password']);
        $u->update($data);

        return back()->with('ok', __('Profile updated.'));
    }

    private function authorizeOwn(Request $r, Appointment $a): void
    {
        $own = $a->user_id === $r->user()->id || ($r->user()->phone && $a->phone === $r->user()->phone);
        abort_unless($own && in_array($a->status, ['requested', 'confirmed'], true), 403);
    }
}
