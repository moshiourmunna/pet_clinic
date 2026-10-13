<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /** The 20 services from the mockup (EN + BN). */
    public function run(): void
    {
        foreach ([
            ['slug' => 'general-check-up', 'icon' => 'fa-stethoscope', 'name_en' => 'General Check-up', 'name_bn' => 'সাধারণ চেকআপ', 'description_en' => 'Complete physical exam and wellness advice.', 'description_bn' => 'সম্পূর্ণ শারীরিক পরীক্ষা ও সুস্বাস্থ্য পরামর্শ।', 'price' => 800, 'is_featured' => true, 'sort' => 1],
            ['slug' => 'vaccination', 'icon' => 'fa-syringe', 'name_en' => 'Vaccination', 'name_bn' => 'টিকা প্রদান', 'description_en' => 'Core and lifestyle vaccines on schedule.', 'description_bn' => 'নিয়মিত সময়সূচিতে প্রয়োজনীয় টিকা।', 'price' => 500, 'is_featured' => true, 'sort' => 2],
            ['slug' => '24-7-emergency', 'icon' => 'fa-truck-medical', 'name_en' => '24/7 Emergency', 'name_bn' => '২৪/৭ জরুরি সেবা', 'description_en' => 'Round-the-clock critical and trauma care.', 'description_bn' => 'দিন-রাত জরুরি ও দুর্ঘটনা চিকিৎসা।', 'price' => 1500, 'is_featured' => true, 'sort' => 3],
            ['slug' => 'surgery', 'icon' => 'fa-user-doctor', 'name_en' => 'Surgery', 'name_bn' => 'সার্জারি', 'description_en' => 'Soft-tissue and orthopedic procedures.', 'description_bn' => 'সফট-টিস্যু ও অর্থোপেডিক অপারেশন।', 'price' => 8000, 'is_featured' => true, 'sort' => 4],
            ['slug' => 'spay-neuter', 'icon' => 'fa-venus-mars', 'name_en' => 'Spay & Neuter', 'name_bn' => 'বন্ধ্যাকরণ', 'description_en' => 'Safe sterilization with fast recovery.', 'description_bn' => 'নিরাপদ বন্ধ্যাকরণ ও দ্রুত সুস্থতা।', 'price' => 5000, 'is_featured' => true, 'sort' => 5],
            ['slug' => 'dental-care', 'icon' => 'fa-tooth', 'name_en' => 'Dental Care', 'name_bn' => 'দাঁতের চিকিৎসা', 'description_en' => 'Scaling, polishing and extractions.', 'description_bn' => 'স্কেলিং, পলিশিং ও দাঁত তোলা।', 'price' => 3000, 'is_featured' => true, 'sort' => 6],
            ['slug' => 'laboratory-tests', 'icon' => 'fa-flask-vial', 'name_en' => 'Laboratory Tests', 'name_bn' => 'ল্যাব পরীক্ষা', 'description_en' => 'In-house blood, urine and stool tests.', 'description_bn' => 'রক্ত, প্রস্রাব ও মল পরীক্ষা।', 'price' => 600, 'is_featured' => true, 'sort' => 7],
            ['slug' => 'x-ray-ultrasound', 'icon' => 'fa-x-ray', 'name_en' => 'X-Ray & Ultrasound', 'name_bn' => 'এক্স-রে ও আল্ট্রাসাউন্ড', 'description_en' => 'Digital imaging for accurate diagnosis.', 'description_bn' => 'নির্ভুল রোগ নির্ণয়ে ডিজিটাল ইমেজিং।', 'price' => 1200, 'is_featured' => true, 'sort' => 8],
            ['slug' => 'grooming', 'icon' => 'fa-scissors', 'name_en' => 'Grooming', 'name_bn' => 'গ্রুমিং', 'description_en' => 'Bath, haircut, nail trim and ear cleaning.', 'description_bn' => 'গোসল, চুল কাটা, নখ কাটা ও কান পরিষ্কার।', 'price' => 1200, 'is_featured' => false, 'sort' => 9],
            ['slug' => 'pet-boarding', 'icon' => 'fa-house-chimney', 'name_en' => 'Pet Boarding', 'name_bn' => 'পেট বোর্ডিং', 'description_en' => 'Safe, comfortable stays while you travel.', 'description_bn' => 'আপনার অনুপস্থিতিতে নিরাপদ থাকার ব্যবস্থা।', 'price' => 700, 'is_featured' => false, 'sort' => 10, 'price_unit_en' => '/day', 'price_unit_bn' => '/দিন'],
            ['slug' => 'deworming-tick-control', 'icon' => 'fa-bug', 'name_en' => 'Deworming & Tick Control', 'name_bn' => 'কৃমি ও এঁটুলি নিয়ন্ত্রণ', 'description_en' => 'Parasite prevention and treatment.', 'description_bn' => 'পরজীবী প্রতিরোধ ও চিকিৎসা।', 'price' => 300, 'is_featured' => false, 'sort' => 11],
            ['slug' => 'dermatology', 'icon' => 'fa-hand-dots', 'name_en' => 'Dermatology', 'name_bn' => 'চর্মরোগ চিকিৎসা', 'description_en' => 'Skin, coat and allergy treatment.', 'description_bn' => 'ত্বক, লোম ও অ্যালার্জির চিকিৎসা।', 'price' => 900, 'is_featured' => false, 'sort' => 12],
            ['slug' => 'eye-care', 'icon' => 'fa-eye', 'name_en' => 'Eye Care', 'name_bn' => 'চোখের চিকিৎসা', 'description_en' => 'Eye exams and ophthalmic treatment.', 'description_bn' => 'চোখের পরীক্ষা ও চিকিৎসা।', 'price' => 900, 'is_featured' => false, 'sort' => 13],
            ['slug' => 'orthopedics', 'icon' => 'fa-bone', 'name_en' => 'Orthopedics', 'name_bn' => 'অর্থোপেডিক্স', 'description_en' => 'Fracture, joint and bone care.', 'description_bn' => 'হাড়, জোড়া ও ভাঙার চিকিৎসা।', 'price' => 2000, 'is_featured' => false, 'sort' => 14],
            ['slug' => 'nutrition-diet', 'icon' => 'fa-apple-whole', 'name_en' => 'Nutrition & Diet', 'name_bn' => 'পুষ্টি ও খাদ্য পরামর্শ', 'description_en' => 'Custom diet plans for every life stage.', 'description_bn' => 'প্রতিটি বয়সের জন্য খাদ্য পরিকল্পনা।', 'price' => 600, 'is_featured' => false, 'sort' => 15],
            ['slug' => 'microchipping', 'icon' => 'fa-microchip', 'name_en' => 'Microchipping', 'name_bn' => 'মাইক্রোচিপিং', 'description_en' => 'Permanent ID to bring lost pets home.', 'description_bn' => 'হারানো পোষা প্রাণী খুঁজে পেতে স্থায়ী আইডি।', 'price' => 1500, 'is_featured' => false, 'sort' => 16],
            ['slug' => 'online-consultation', 'icon' => 'fa-video', 'name_en' => 'Online Consultation', 'name_bn' => 'অনলাইন পরামর্শ', 'description_en' => 'Video call with our vets from home.', 'description_bn' => 'ঘরে বসে ভিডিও কলে ডাক্তারের পরামর্শ।', 'price' => 500, 'is_featured' => false, 'sort' => 17],
            ['slug' => 'home-visit', 'icon' => 'fa-car', 'name_en' => 'Home Visit', 'name_bn' => 'হোম ভিজিট', 'description_en' => 'Vet care at your doorstep.', 'description_bn' => 'আপনার দোরগোড়ায় পশু চিকিৎসা।', 'price' => 2000, 'is_featured' => false, 'sort' => 18],
            ['slug' => 'bird-exotic-care', 'icon' => 'fa-dove', 'name_en' => 'Bird & Exotic Care', 'name_bn' => 'পাখি ও বিশেষ প্রাণী', 'description_en' => 'Care for birds, rabbits and exotics.', 'description_bn' => 'পাখি, খরগোশ ও বিশেষ প্রাণীর চিকিৎসা।', 'price' => 900, 'is_featured' => false, 'sort' => 19],
            ['slug' => 'pet-pharmacy', 'icon' => 'fa-pills', 'name_en' => 'Pet Pharmacy', 'name_bn' => 'পেট ফার্মেসি', 'description_en' => 'Medicines, food and accessories.', 'description_bn' => 'ওষুধ, খাবার ও আনুষঙ্গিক পণ্য।', 'price' => 0, 'is_featured' => false, 'sort' => 20],
        ] as $row) {
            Service::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
