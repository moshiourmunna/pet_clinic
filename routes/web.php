<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Api\SlotController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// ---- Public site
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services');
    Route::get('/pricing', 'pricing')->name('pricing');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');
});
Route::get('/lang/{locale}', LocaleController::class)->whereIn('locale', ['en', 'bn'])->name('locale.switch');

Route::middleware('throttle:contact')->group(function () {
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::post('/newsletter', [ContactController::class, 'subscribe'])->name('newsletter.store');
});

Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
Route::get('/api/doctors/{doctor:id}/slots', SlotController::class)->name('api.slots');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

// ---- Booking (guests allowed)
Route::get('/appointment', [AppointmentController::class, 'create'])->name('appointment.create');
Route::post('/appointment', [AppointmentController::class, 'store'])->middleware('throttle:booking')->name('appointment.store');
Route::get('/appointment/{code}', [AppointmentController::class, 'show'])->name('appointment.success');

// ---- Payments (optional)
Route::get('/pay/{code}', [PaymentController::class, 'start'])->name('payment.start');
Route::get('/payment/simulate/{payment:tran_id}', [PaymentController::class, 'simulate'])->name('payment.simulate');
Route::match(['get', 'post'], '/payment/{gateway}/success', [PaymentController::class, 'success'])->name('payment.success');
Route::match(['get', 'post'], '/payment/{gateway}/callback', [PaymentController::class, 'success'])->name('payment.callback');
Route::match(['get', 'post'], '/payment/{gateway}/fail', [PaymentController::class, 'fail'])->name('payment.fail');
Route::match(['get', 'post'], '/payment/{gateway}/cancel', [PaymentController::class, 'fail'])->name('payment.cancel');
Route::post('/payment/{gateway}/ipn', [PaymentController::class, 'ipn'])->name('payment.ipn');

// ---- Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:login');
    Route::post('/otp/send', [OtpController::class, 'send'])->middleware('throttle:otp')->name('otp.send');
    Route::post('/otp/verify', [OtpController::class, 'verify'])->middleware('throttle:login')->name('otp.verify');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ---- Customer dashboard
Route::middleware('auth')->prefix('dashboard')->name('dashboard')->group(function () {
    Route::get('/', [Customer\DashboardController::class, 'index']);
    Route::get('/appointments', [Customer\DashboardController::class, 'appointments'])->name('.appointments');
    Route::post('/appointments/{appointment}/cancel', [Customer\DashboardController::class, 'cancel'])->name('.appointments.cancel');
    Route::put('/appointments/{appointment}/reschedule', [Customer\DashboardController::class, 'reschedule'])->name('.appointments.reschedule');
    Route::get('/records', [Customer\DashboardController::class, 'records'])->name('.records');
    Route::get('/vaccinations', [Customer\DashboardController::class, 'vaccinations'])->name('.vaccinations');
    Route::get('/payments', [Customer\DashboardController::class, 'payments'])->name('.payments');
    Route::get('/settings', [Customer\DashboardController::class, 'settings'])->name('.settings');
    Route::put('/settings', [Customer\DashboardController::class, 'updateSettings']);
    Route::resource('pets', Customer\PetController::class)->except('show')->names(['index' => '.pets.index', 'create' => '.pets.create', 'store' => '.pets.store', 'edit' => '.pets.edit', 'update' => '.pets.update', 'destroy' => '.pets.destroy']);
});

// ---- Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [Admin\AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}', [Admin\AppointmentController::class, 'update'])->name('appointments.update');
    Route::get('/doctors/{doctor}/schedules', [Admin\ScheduleController::class, 'edit'])->name('doctors.schedules');
    Route::put('/doctors/{doctor}/schedules', [Admin\ScheduleController::class, 'update']);
    Route::get('/messages', [Admin\MessageController::class, 'index'])->name('messages.index');
    Route::patch('/messages/{message}', [Admin\MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [Admin\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/settings', [Admin\SettingController::class, 'edit'])->name('settings');
    Route::put('/settings', [Admin\SettingController::class, 'update']);
    // Content CRUD: services, doctors, plans, posts, faqs, gallery, testimonials
    Route::pattern('resource', 'services|doctors|plans|posts|faqs|gallery|testimonials');
    Route::get('/{resource}', [Admin\ContentController::class, 'index'])->name('content.index');
    Route::get('/{resource}/create', [Admin\ContentController::class, 'create'])->name('content.create');
    Route::post('/{resource}', [Admin\ContentController::class, 'store'])->name('content.store');
    Route::get('/{resource}/{id}/edit', [Admin\ContentController::class, 'edit'])->name('content.edit');
    Route::put('/{resource}/{id}', [Admin\ContentController::class, 'update'])->name('content.update');
    Route::delete('/{resource}/{id}', [Admin\ContentController::class, 'destroy'])->name('content.destroy');
});
