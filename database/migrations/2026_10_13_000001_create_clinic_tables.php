<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('icon', 60)->default('fa-paw');
            $t->string('name_en')->nullable();
            $t->string('name_bn')->nullable();
            $t->string('description_en', 500)->nullable();
            $t->string('description_bn', 500)->nullable();
            $t->unsignedInteger('price')->default(0);
            $t->string('price_unit_en', 30)->nullable();
            $t->string('price_unit_bn', 30)->nullable();
            $t->boolean('is_featured')->default(false);
            $t->boolean('is_active')->default(true);
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('doctors', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('name_en')->nullable();
            $t->string('name_bn')->nullable();
            $t->string('title_en')->nullable();       // speciality label
            $t->string('title_bn')->nullable();
            $t->string('degree_en')->nullable();
            $t->string('degree_bn')->nullable();
            $t->text('bio_en')->nullable();
            $t->text('bio_bn')->nullable();
            $t->string('speciality', 40)->index();    // filter key: surgery|medicine|dermatology|emergency|exotic
            $t->unsignedTinyInteger('experience')->default(0);
            $t->unsignedInteger('fee')->default(0);
            $t->decimal('rating', 2, 1)->default(5.0);
            $t->unsignedInteger('reviews')->default(0);
            $t->unsignedInteger('patients')->default(0);
            $t->string('photo')->nullable();
            $t->string('bvc_reg', 40)->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('doctor_schedules', function (Blueprint $t) {
            $t->id();
            $t->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('day_of_week'); // 0=Sun .. 6=Sat (Carbon)
            $t->time('start_time');
            $t->time('end_time');
            $t->unsignedSmallInteger('slot_minutes')->default(30);
            $t->timestamps();
            $t->index(['doctor_id', 'day_of_week']);
        });

        Schema::create('pets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('species', 30);
            $t->string('breed')->nullable();
            $t->string('age', 30)->nullable();
            $t->string('gender', 10)->nullable();
            $t->decimal('weight', 5, 2)->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('appointments', function (Blueprint $t) {
            $t->id();
            $t->string('code', 20)->unique();
            $t->string('type', 10);                         // slot|request
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('pet_id')->nullable()->constrained()->nullOnDelete();
            $t->string('owner_name');
            $t->string('phone', 20)->index();
            $t->string('email')->nullable();
            $t->string('pet_name');
            $t->string('pet_type', 30);
            $t->string('pet_age_breed')->nullable();
            $t->foreignId('service_id')->constrained();
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $t->date('date');
            $t->time('time')->nullable();
            $t->string('visit_type', 20)->default('clinic'); // clinic|online|home
            $t->text('symptoms')->nullable();
            $t->string('status', 20)->default('requested')->index();
            $t->string('payment_method', 20)->default('clinic');
            $t->string('payment_status', 20)->default('unpaid');
            $t->unsignedInteger('fee')->default(0);
            // "doctor_id|date|time" while active; NULL for requests/cancelled → DB-level double-booking guard
            $t->string('slot_key', 60)->nullable()->unique();
            $t->text('admin_note')->nullable();
            $t->timestamps();
        });

        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('gateway', 20);
            $t->string('tran_id', 64)->unique();
            $t->string('gateway_ref')->nullable();
            $t->unsignedInteger('amount');
            $t->string('currency', 3)->default('BDT');
            $t->string('status', 20)->default('pending');
            $t->json('payload')->nullable();
            $t->timestamps();
        });

        Schema::create('medical_records', function (Blueprint $t) {
            $t->id();
            $t->foreignId('pet_id')->constrained()->cascadeOnDelete();
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $t->date('visit_date');
            $t->string('diagnosis');
            $t->text('treatment')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('vaccinations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('pet_id')->constrained()->cascadeOnDelete();
            $t->string('vaccine');
            $t->date('given_on')->nullable();
            $t->date('due_on')->nullable();
            $t->timestamps();
        });

        Schema::create('plans', function (Blueprint $t) {
            $t->id();
            $t->string('name_en')->nullable();
            $t->string('name_bn')->nullable();
            $t->unsignedInteger('price');
            $t->string('period_en', 30)->nullable();
            $t->string('period_bn', 30)->nullable();
            $t->json('features_en')->nullable();
            $t->json('features_bn')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('title_en')->nullable();
            $t->string('title_bn')->nullable();
            $t->string('category_en')->nullable();
            $t->string('category_bn')->nullable();
            $t->string('excerpt_en', 500)->nullable();
            $t->string('excerpt_bn', 500)->nullable();
            $t->longText('body_en')->nullable();
            $t->longText('body_bn')->nullable();
            $t->string('image')->nullable();
            $t->timestamp('published_at')->nullable()->index();
            $t->timestamps();
        });

        Schema::create('faqs', function (Blueprint $t) {
            $t->id();
            $t->string('question_en')->nullable();
            $t->string('question_bn')->nullable();
            $t->text('answer_en')->nullable();
            $t->text('answer_bn')->nullable();
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('gallery_images', function (Blueprint $t) {
            $t->id();
            $t->string('image');
            $t->string('caption_en')->nullable();
            $t->string('caption_bn')->nullable();
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('subtitle_en')->nullable();
            $t->string('subtitle_bn')->nullable();
            $t->text('text_en')->nullable();
            $t->text('text_bn')->nullable();
            $t->unsignedTinyInteger('rating')->default(5);
            $t->string('photo')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone', 20)->nullable();
            $t->string('email')->nullable();
            $t->text('message');
            $t->timestamp('read_at')->nullable();
            $t->timestamps();
        });

        Schema::create('subscribers', function (Blueprint $t) {
            $t->id();
            $t->string('email')->unique();
            $t->timestamps();
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });

        Schema::create('otp_codes', function (Blueprint $t) {
            $t->id();
            $t->string('phone', 20)->index();
            $t->string('code_hash');
            $t->unsignedTinyInteger('attempts')->default(0);
            $t->timestamp('expires_at');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['otp_codes','settings','subscribers','contact_messages','testimonials','gallery_images','faqs','posts','plans','vaccinations','medical_records','payments','appointments','pets','doctor_schedules','doctors','services'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
