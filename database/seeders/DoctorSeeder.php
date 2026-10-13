<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // day_of_week: 0=Sun 1=Mon 2=Tue 3=Wed 4=Thu 5=Fri 6=Sat
        $doctors = [
            ['dr-arif-rahman', 'Dr. Arif Rahman', 'ডা. আরিফ রহমান', 'Senior Surgeon', 'সিনিয়র সার্জন', 'DVM, MS (Surgery)', 'ডিভিএম, এমএস (সার্জারি)', 'surgery', 12, 1000, 4.9, 320, 5000, 'assets/img3.jpg',
                '12 years of experience in soft-tissue and orthopedic surgery. Trained in Thailand and India; BVC registration #1234.', '১২ বছরের অভিজ্ঞ সার্জন, সফট-টিস্যু ও অর্থোপেডিক সার্জারিতে বিশেষজ্ঞ।',
                [[6, '10:00', '14:00'], [0, '10:00', '14:00'], [1, '10:00', '14:00'], [2, '17:00', '21:00'], [3, '17:00', '21:00']]],
            ['dr-nusrat-jahan', 'Dr. Nusrat Jahan', 'ডা. নুসরাত জাহান', 'Small Animal Medicine', 'ছোট প্রাণী চিকিৎসা', 'DVM, MS (Medicine)', 'ডিভিএম, এমএস (মেডিসিন)', 'medicine', 9, 800, 4.9, 280, 4200, 'assets/img11.jpg',
                'Internal medicine specialist for dogs and cats with a gentle, fear-free approach.', 'কুকুর ও বিড়ালের মেডিসিন বিশেষজ্ঞ, ভয়মুক্ত পরিচর্যায় অভিজ্ঞ।',
                [[6, '16:00', '21:00'], [0, '16:00', '21:00'], [1, '16:00', '21:00'], [2, '10:00', '14:00'], [3, '10:00', '14:00'], [4, '10:00', '14:00']]],
            ['dr-tanvir-hasan', 'Dr. Tanvir Hasan', 'ডা. তানভীর হাসান', 'Emergency & Critical Care', 'জরুরি ও ক্রিটিক্যাল কেয়ার', 'DVM, PG (Critical Care)', 'ডিভিএম, পিজি (ক্রিটিক্যাল কেয়ার)', 'emergency', 8, 1200, 4.9, 210, 3500, 'assets/img10.jpg',
                'Leads our 24/7 emergency team: trauma, poisoning and intensive care.', 'আমাদের ২৪/৭ জরুরি দলের প্রধান: দুর্ঘটনা, বিষক্রিয়া ও নিবিড় পরিচর্যা।',
                [[0, '09:00', '13:00'], [1, '09:00', '13:00'], [2, '09:00', '13:00'], [3, '18:00', '22:00'], [4, '18:00', '22:00'], [5, '10:00', '14:00']]],
            ['dr-farhana-akter', 'Dr. Farhana Akter', 'ডা. ফারহানা আক্তার', 'Dermatology & Dental', 'চর্ম ও দন্ত', 'DVM, MS (Dermatology)', 'ডিভিএম, এমএস (চর্মরোগ)', 'dermatology', 10, 900, 4.9, 250, 3900, 'assets/img9.jpg',
                'Skin, allergy and dental specialist; scaling, polishing and extractions.', 'চর্ম, অ্যালার্জি ও দাঁতের চিকিৎসা বিশেষজ্ঞ।',
                [[6, '10:00', '13:00'], [1, '15:00', '20:00'], [2, '15:00', '20:00'], [3, '15:00', '20:00'], [4, '10:00', '13:00']]],
            ['dr-sabbir-hossain', 'Dr. Sabbir Hossain', 'ডা. সাব্বির হোসেন', 'Bird & Exotic Pets', 'পাখি ও বিশেষ প্রাণী', 'DVM, MSc (Avian Medicine)', 'ডিভিএম, এমএসসি (পাখি চিকিৎসা)', 'exotic', 7, 900, 4.8, 140, 2100, 'assets/img12.jpg',
                'Care for birds, rabbits, guinea pigs and other exotic companions.', 'পাখি, খরগোশ, গিনিপিগসহ বিশেষ প্রাণীর চিকিৎসা।',
                [[6, '15:00', '19:00'], [0, '15:00', '19:00'], [4, '15:00', '19:00'], [5, '15:00', '19:00']]],
            ['dr-mahmuda-khan', 'Dr. Mahmuda Khan', 'ডা. মাহমুদা খান', 'Orthopedic Surgery', 'অর্থোপেডিক সার্জারি', 'DVM, MS (Orthopedics)', 'ডিভিএম, এমএস (অর্থোপেডিক্স)', 'surgery', 11, 1100, 4.9, 190, 2800, 'assets/img2.jpg',
                'Fracture repair, joint surgery and post-operative rehabilitation.', 'হাড় ভাঙা, জয়েন্ট সার্জারি ও অপারেশন-পরবর্তী পুনর্বাসন।',
                [[0, '17:00', '21:00'], [2, '17:00', '21:00'], [4, '17:00', '21:00'], [5, '17:00', '21:00']]],
        ];

        foreach ($doctors as $i => [$slug, $nen, $nbn, $ten, $tbn, $den, $dbn, $sp, $exp, $fee, $rating, $reviews, $patients, $photo, $ben, $bbn, $sched]) {
            $d = Doctor::updateOrCreate(['slug' => $slug], [
                'name_en' => $nen, 'name_bn' => $nbn, 'title_en' => $ten, 'title_bn' => $tbn, 'degree_en' => $den, 'degree_bn' => $dbn,
                'bio_en' => $ben, 'bio_bn' => $bbn, 'speciality' => $sp, 'experience' => $exp, 'fee' => $fee, 'rating' => $rating,
                'reviews' => $reviews, 'patients' => $patients, 'photo' => $photo, 'bvc_reg' => 'BVC-'.(1234 + $i), 'sort' => $i + 1,
            ]);
            $d->schedules()->delete();
            foreach ($sched as [$day, $start, $end]) {
                $d->schedules()->create(['day_of_week' => $day, 'start_time' => $start, 'end_time' => $end, 'slot_minutes' => 30]);
            }
        }
    }
}
