<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ContactMessage;
use App\Models\Doctor;
use App\Models\Payment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'today' => Appointment::whereDate('date', today())->where('status', '!=', 'cancelled')->count(),
                'pending' => Appointment::where('status', 'requested')->count(),
                'revenue' => Payment::where('status', 'paid')->whereMonth('created_at', now()->month)->sum('amount'),
                'customers' => User::where('role', 'customer')->count(),
                'doctors' => Doctor::where('is_active', true)->count(),
                'messages' => ContactMessage::whereNull('read_at')->count(),
            ],
            'latest' => Appointment::with('service', 'doctor')->latest()->take(8)->get(),
        ]);
    }
}
