<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@petcareclinic.com'], [
            'name' => 'Clinic Admin', 'phone' => '01700000000', 'role' => 'admin', 'password' => 'password',
        ]);

        $rafiq = User::updateOrCreate(['email' => 'rafiq@example.com'], [
            'name' => 'Rafiq Ahmed', 'phone' => '01711111111', 'role' => 'customer', 'password' => 'password',
        ]);
        $rafiq->pets()->delete();
        $bruno = $rafiq->pets()->create(['name' => 'Bruno', 'species' => 'Dog', 'breed' => 'Labrador', 'age' => '2 yrs', 'gender' => 'male', 'weight' => 24.5]);
        $mini = $rafiq->pets()->create(['name' => 'Mini', 'species' => 'Cat', 'breed' => 'Persian', 'age' => '3 yrs', 'gender' => 'female', 'weight' => 4.2]);
        $rafiq->pets()->create(['name' => 'Tweety', 'species' => 'Bird', 'breed' => 'Budgerigar', 'age' => '1 yr']);

        $arif = Doctor::where('slug', 'dr-arif-rahman')->first();
        $nusrat = Doctor::where('slug', 'dr-nusrat-jahan')->first();
        $svc = fn ($slug) => Service::where('slug', $slug)->value('id');

        Appointment::where('user_id', $rafiq->id)->delete();
        $base = ['user_id' => $rafiq->id, 'owner_name' => $rafiq->name, 'phone' => $rafiq->phone, 'email' => $rafiq->email, 'visit_type' => 'clinic'];

        // upcoming slot booking on Nusrat's next working Saturday at 16:30
        $sat = today()->next(6)->toDateString();
        Appointment::create($base + ['code' => 'PCDEMO001', 'type' => 'slot', 'pet_id' => $bruno->id, 'pet_name' => 'Bruno', 'pet_type' => 'Dog',
            'service_id' => $svc('vaccination'), 'doctor_id' => $nusrat->id, 'date' => $sat, 'time' => '16:30', 'status' => 'confirmed',
            'payment_method' => 'bkash_nagad', 'payment_status' => 'paid', 'fee' => 800, 'slot_key' => Appointment::slotKey($nusrat->id, $sat, '16:30')]);
        $req = Appointment::create($base + ['code' => 'PCDEMO002', 'type' => 'request', 'pet_id' => $mini->id, 'pet_name' => 'Mini', 'pet_type' => 'Cat',
            'service_id' => $svc('dental-care'), 'date' => today()->addDays(5), 'status' => 'requested', 'payment_method' => 'clinic', 'fee' => 3000]);
        $past = Appointment::create($base + ['code' => 'PCDEMO003', 'type' => 'slot', 'pet_id' => $bruno->id, 'pet_name' => 'Bruno', 'pet_type' => 'Dog',
            'service_id' => $svc('general-check-up'), 'doctor_id' => $arif->id, 'date' => today()->subMonth(), 'time' => '10:30', 'status' => 'completed',
            'payment_method' => 'card', 'payment_status' => 'paid', 'fee' => 3400]);

        $first = Appointment::where('code', 'PCDEMO001')->first();
        $first->payments()->create(['user_id' => $rafiq->id, 'gateway' => 'bkash', 'tran_id' => 'TXNDEMO0001', 'gateway_ref' => 'BK7XK29', 'amount' => 800, 'status' => 'paid']);
        $past->payments()->create(['user_id' => $rafiq->id, 'gateway' => 'sslcommerz', 'tran_id' => 'TXNDEMO0002', 'gateway_ref' => 'SSL88213', 'amount' => 3400, 'status' => 'paid']);

        $bruno->medicalRecords()->create(['doctor_id' => $arif->id, 'visit_date' => today()->subMonth(), 'diagnosis' => 'Routine check-up — healthy', 'treatment' => 'Deworming tablet, diet advice', 'notes' => 'Weight 24.5 kg']);
        $mini->medicalRecords()->create(['doctor_id' => $nusrat->id, 'visit_date' => today()->subMonths(3), 'diagnosis' => 'Mild gingivitis', 'treatment' => 'Dental gel for 14 days']);

        $bruno->vaccinations()->create(['vaccine' => 'Rabies', 'given_on' => today()->subYear()->addDays(10), 'due_on' => today()->addDays(10)]);
        $bruno->vaccinations()->create(['vaccine' => 'DHPPi + L', 'given_on' => today()->subMonths(4), 'due_on' => today()->addMonths(8)]);
        $mini->vaccinations()->create(['vaccine' => 'FVRCP', 'given_on' => today()->subMonths(2), 'due_on' => today()->addMonths(10)]);
    }
}
