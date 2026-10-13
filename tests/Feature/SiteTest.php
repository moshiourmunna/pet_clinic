<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_pages_load(): void
    {
        foreach (['home', 'about', 'services', 'doctors.index', 'appointment.create', 'pricing', 'gallery', 'blog.index', 'faq', 'contact', 'login', 'register', 'sitemap'] as $r) {
            $this->get(route($r))->assertOk();
        }
        $this->get('/doctors/dr-arif-rahman')->assertOk()->assertSee('Dr. Arif Rahman');
        $this->get('/blog/puppy-vaccination-schedule')->assertOk();
        $this->get('/this-page-does-not-exist')->assertNotFound()->assertSee('404');
    }

    public function test_seeded_twenty_services(): void
    {
        $this->assertSame(20, Service::count());
        $this->get(route('services'))->assertSee('Pet Pharmacy');
    }

    public function test_locale_switch_changes_language(): void
    {
        $this->get(route('home'))->assertSee('Expert Care for Your', false);
        $this->get(route('locale.switch', 'bn'))->assertRedirect()->assertSessionHas('locale', 'bn');
        $this->get(route('home'))->assertSee('ভালোবাসা ও বিশেষজ্ঞ', false)->assertSee('ডা. আরিফ রহমান');
        $this->get(route('locale.switch', 'en'));
        $this->get(route('home'))->assertSee('Dr. Arif Rahman');
        $this->get('/lang/fr')->assertNotFound();
    }

    public function test_bilingual_fallback_when_one_language_is_empty(): void
    {
        $s = Service::first();
        $s->update(['name_bn' => null, 'name_en' => 'Only English Name']);
        app()->setLocale('bn');
        $this->assertSame('Only English Name', $s->fresh()->name);
        $s->update(['name_en' => '', 'name_bn' => 'শুধু বাংলা']);
        app()->setLocale('en');
        $this->assertSame('শুধু বাংলা', $s->fresh()->name);
    }

    public function test_doctor_filter_returns_json_for_live_search(): void
    {
        $res = $this->getJson(route('doctors.index', ['q' => 'Nusrat']))->assertOk();
        $this->assertSame(1, $res->json('count'));
        $this->assertStringContainsString('Nusrat', $res->json('html'));
        $this->assertSame(2, $this->getJson(route('doctors.index', ['speciality' => 'surgery']))->json('count'));
    }

    public function test_contact_and_newsletter_ajax(): void
    {
        $this->postJson(route('contact.store'), [])->assertStatus(422)->assertJsonValidationErrors(['name', 'message']);
        $this->postJson(route('contact.store'), ['name' => 'A', 'phone' => '01700000001', 'message' => 'Hello there'])->assertOk()->assertJsonStructure(['message']);
        $this->postJson(route('newsletter.store'), ['email' => 'x@example.com'])->assertOk();
        $this->assertDatabaseHas('contact_messages', ['name' => 'A']);
        $this->assertDatabaseHas('subscribers', ['email' => 'x@example.com']);
    }

    public function test_register_and_login_with_phone_or_email(): void
    {
        $this->post(route('register'), ['name' => 'New User', 'phone' => '01812345678', 'email' => 'new@example.com', 'password' => 'secret123', 'password_confirmation' => 'secret123'])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->post(route('logout'));
        $this->assertGuest();

        $this->post(route('login'), ['login' => '01812345678', 'password' => 'secret123'])->assertRedirect(route('dashboard'));
        $this->post(route('logout'));
        $this->post(route('login'), ['login' => 'new@example.com', 'password' => 'secret123'])->assertRedirect(route('dashboard'));
        $this->post(route('logout'));
        $this->post(route('login'), ['login' => 'new@example.com', 'password' => 'wrong'])->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_otp_login_flow_with_log_driver(): void
    {
        $code = null;
        Log::listen(function ($e) use (&$code) {
            if (preg_match('/login code is (\d{6})/', $e->message, $m)) $code = $m[1];
        });
        $this->post(route('otp.send'), ['phone' => '01711111111'])->assertSessionHas('otp_sent');
        $this->assertNotNull($code);
        $this->post(route('otp.verify'), ['phone' => '01711111111', 'code' => '000000'])->assertSessionHasErrors('code');
        $this->post(route('otp.verify'), ['phone' => '01711111111', 'code' => $code])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs(User::where('phone', '01711111111')->first());
    }

    public function test_customer_dashboard_pages(): void
    {
        $u = User::where('email', 'rafiq@example.com')->first();
        foreach (['dashboard', 'dashboard.appointments', 'dashboard.pets.index', 'dashboard.pets.create', 'dashboard.records', 'dashboard.vaccinations', 'dashboard.payments', 'dashboard.settings'] as $r) {
            $this->actingAs($u)->get(route($r))->assertOk();
        }
        $this->actingAs($u)->post(route('dashboard.pets.store'), ['name' => 'Kitty', 'species' => 'Cat'])->assertRedirect();
        $this->assertDatabaseHas('pets', ['name' => 'Kitty', 'user_id' => $u->id]);
        $this->get(route('logout'));
    }

    public function test_dashboard_requires_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_admin_access_is_role_based(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $customer = User::where('email', 'rafiq@example.com')->first();
        $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();

        $admin = User::where('email', 'admin@petcareclinic.com')->first();
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        foreach (['services', 'doctors', 'plans', 'posts', 'faqs', 'gallery', 'testimonials'] as $res) {
            $this->actingAs($admin)->get(route('admin.content.index', $res))->assertOk();
            $this->actingAs($admin)->get(route('admin.content.create', $res))->assertOk();
        }
        foreach (['admin.appointments.index', 'admin.messages.index', 'admin.settings'] as $r) {
            $this->actingAs($admin)->get(route($r))->assertOk();
        }
        $this->actingAs($admin)->get(route('admin.doctors.schedules', 'dr-arif-rahman'))->assertOk();
    }

    public function test_admin_can_confirm_request_and_manage_content(): void
    {
        $admin = User::where('email', 'admin@petcareclinic.com')->first();
        $a = \App\Models\Appointment::where('code', 'PCDEMO002')->first();
        $this->actingAs($admin)->patch(route('admin.appointments.update', $a), ['status' => 'confirmed', 'doctor_id' => 1])->assertRedirect();
        $this->assertSame('confirmed', $a->fresh()->status);

        $this->actingAs($admin)->post(route('admin.content.store', 'faqs'), ['question_bn' => 'শুধু বাংলা প্রশ্ন?', 'answer_bn' => 'হ্যাঁ।', 'sort' => 9])->assertRedirect();
        $this->assertDatabaseHas('faqs', ['question_bn' => 'শুধু বাংলা প্রশ্ন?']);
        $this->actingAs($admin)->post(route('admin.content.store', 'faqs'), ['sort' => 1])->assertSessionHasErrors('question_en');

        $this->actingAs($admin)->put(route('admin.settings'), ['phone' => '+880 1999-999999'] + \App\Models\Setting::DEFAULTS)->assertRedirect();
        $this->get(route('home'))->assertSee('+880 1999-999999');
    }
}
