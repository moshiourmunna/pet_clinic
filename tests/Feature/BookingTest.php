<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private Doctor $doctor;
    private Service $service;
    private string $date;

    protected function setUp(): void
    {
        parent::setUp();
        $this->doctor = Doctor::factory()->everyDay()->create(['fee' => 1000]);
        $this->service = Service::factory()->create(['price' => 500]);
        $this->date = today()->addDays(2)->toDateString();
    }

    private function payload(array $over = []): array
    {
        return array_merge([
            'type' => 'slot', 'owner_name' => 'Rafiq Ahmed', 'phone' => '01711111111', 'email' => 'r@example.com',
            'pet_name' => 'Bruno', 'pet_type' => 'Dog', 'service_id' => $this->service->id, 'doctor_id' => $this->doctor->id,
            'date' => $this->date, 'time' => '10:30', 'visit_type' => 'clinic', 'payment_method' => 'clinic', 'terms' => '1',
        ], $over);
    }

    public function test_slots_api_returns_schedule_and_marks_booked_slots(): void
    {
        $res = $this->getJson(route('api.slots', $this->doctor).'?date='.$this->date)->assertOk();
        $this->assertCount(4, $res->json('slots')); // 10:00–12:00, 30-min
        $this->assertTrue(collect($res->json('slots'))->every('available'));

        $this->post(route('appointment.store'), $this->payload());
        $slots = collect($this->getJson(route('api.slots', $this->doctor).'?date='.$this->date)->json('slots'));
        $this->assertFalse($slots->firstWhere('time', '10:30')['available']);
        $this->assertTrue($slots->firstWhere('time', '10:00')['available']);
    }

    public function test_guest_can_book_a_slot(): void
    {
        $this->post(route('appointment.store'), $this->payload())->assertRedirect();

        $a = Appointment::first();
        $this->assertSame('slot', $a->type);
        $this->assertSame('confirmed', $a->status);
        $this->assertSame(1000, $a->fee);
        $this->assertNull($a->user_id);
        $this->assertSame(Appointment::slotKey($this->doctor->id, $this->date, '10:30'), $a->slot_key);
        $this->get(route('appointment.success', $a->code))->assertOk()->assertSee($a->code);
    }

    public function test_request_mode_needs_no_doctor_or_time(): void
    {
        $this->post(route('appointment.store'), $this->payload(['type' => 'request', 'doctor_id' => null, 'time' => null]))->assertRedirect();

        $a = Appointment::first();
        $this->assertSame('request', $a->type);
        $this->assertSame('requested', $a->status);
        $this->assertNull($a->doctor_id);
        $this->assertNull($a->time);
        $this->assertNull($a->slot_key);
    }

    public function test_slot_mode_requires_doctor_and_time(): void
    {
        $this->post(route('appointment.store'), $this->payload(['doctor_id' => null, 'time' => null]))
            ->assertSessionHasErrors(['doctor_id', 'time']);
    }

    public function test_phone_is_required_and_validated(): void
    {
        $this->post(route('appointment.store'), $this->payload(['phone' => '']))->assertSessionHasErrors('phone');
        $this->post(route('appointment.store'), $this->payload(['phone' => '12345']))->assertSessionHasErrors('phone');
        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_double_booking_is_rejected(): void
    {
        $this->post(route('appointment.store'), $this->payload())->assertRedirect();
        $this->post(route('appointment.store'), $this->payload(['phone' => '01822222222']))->assertSessionHasErrors('time');
        $this->assertDatabaseCount('appointments', 1);
    }

    public function test_unique_slot_key_blocks_race_condition_at_db_level(): void
    {
        $this->post(route('appointment.store'), $this->payload());
        $this->expectException(\Illuminate\Database\QueryException::class);
        Appointment::create(Appointment::first()->replicate()->fill(['code' => 'DUPLICATE1'])->getAttributes());
    }

    public function test_slot_outside_schedule_is_rejected(): void
    {
        $this->post(route('appointment.store'), $this->payload(['time' => '15:00']))->assertSessionHasErrors('time');
    }

    public function test_cancelled_slot_becomes_available_again(): void
    {
        $this->post(route('appointment.store'), $this->payload());
        Appointment::first()->cancel();
        $this->post(route('appointment.store'), $this->payload(['phone' => '01822222222']))->assertRedirect();
        $this->assertDatabaseCount('appointments', 2);
    }

    public function test_online_payment_redirects_to_gateway_and_can_be_paid(): void
    {
        $res = $this->post(route('appointment.store'), $this->payload(['payment_method' => 'bkash_nagad']));
        $a = Appointment::first();
        $res->assertRedirect(route('payment.start', $a->code));

        $this->get(route('payment.start', $a->code))->assertRedirect(); // simulate page (no keys)
        $payment = $a->payments()->first();
        $this->get(route('payment.simulate', [$payment->tran_id, 'result' => 'success']))->assertRedirect(route('appointment.success', $a->code));
        $this->assertSame('paid', $a->fresh()->payment_status);
    }

    public function test_logged_in_user_booking_is_linked_and_can_be_cancelled(): void
    {
        $user = User::factory()->create(['phone' => '01711111111']);
        $this->actingAs($user)->post(route('appointment.store'), $this->payload());
        $a = Appointment::first();
        $this->assertSame($user->id, $a->user_id);

        $this->actingAs($user)->post(route('dashboard.appointments.cancel', $a))->assertRedirect();
        $this->assertSame('cancelled', $a->fresh()->status);
        $this->assertNull($a->fresh()->slot_key);
    }

    public function test_other_user_cannot_cancel_appointment(): void
    {
        $this->post(route('appointment.store'), $this->payload());
        $other = User::factory()->create(['phone' => '01999999999']);
        $this->actingAs($other)->post(route('dashboard.appointments.cancel', Appointment::first()))->assertForbidden();
    }

    public function test_reschedule_moves_slot(): void
    {
        $user = User::factory()->create(['phone' => '01711111111']);
        $this->actingAs($user)->post(route('appointment.store'), $this->payload());
        $a = Appointment::first();
        $this->actingAs($user)->put(route('dashboard.appointments.reschedule', $a), ['date' => $this->date, 'time' => '11:00'])->assertSessionHasNoErrors();
        $this->assertSame('11:00', substr($a->fresh()->time, 0, 5));
    }
}
