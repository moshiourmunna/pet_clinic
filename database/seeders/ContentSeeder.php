<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\Plan;
use App\Models\Post;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        Plan::truncate();
        $plans = [
            ['Basic Consultation', 'বেসিক পরামর্শ', 800, 'visit', 'ভিজিট', false,
                ['Physical exam', 'Doctor consultation', 'Prescription', 'Free follow-up (7 days)'],
                ['শারীরিক পরীক্ষা', 'ডাক্তারের পরামর্শ', 'প্রেসক্রিপশন', 'ফ্রি ফলো-আপ (৭ দিন)']],
            ['Wellness Plan', 'ওয়েলনেস প্ল্যান', 9500, 'year', 'বছর', true,
                ['4 check-ups a year', 'All core vaccines', 'Deworming & tick control', 'Annual blood test', '10% off surgery'],
                ['বছরে ৪টি চেকআপ', 'সব প্রয়োজনীয় টিকা', 'কৃমি ও এঁটুলি নিয়ন্ত্রণ', 'বার্ষিক রক্ত পরীক্ষা', 'সার্জারিতে ১০% ছাড়']],
            ['Premium Care', 'প্রিমিয়াম কেয়ার', 18000, 'year', 'বছর', false,
                ['Unlimited consultations', 'Vaccines + dental cleaning', '2 grooming sessions', 'Priority emergency', '20% off all services'],
                ['আনলিমিটেড পরামর্শ', 'টিকা + দাঁত পরিষ্কার', '২টি গ্রুমিং', 'অগ্রাধিকার জরুরি সেবা', 'সব সেবায় ২০% ছাড়']],
        ];
        foreach ($plans as $i => [$nen, $nbn, $price, $pen, $pbn, $hot, $fen, $fbn]) {
            Plan::create(['name_en' => $nen, 'name_bn' => $nbn, 'price' => $price, 'period_en' => $pen, 'period_bn' => $pbn,
                'is_featured' => $hot, 'features_en' => $fen, 'features_bn' => $fbn, 'sort' => $i + 1]);
        }

        Post::truncate();
        $posts = [
            ['puppy-vaccination-schedule', 'Puppy Vaccination Schedule: A Complete Guide', 'কুকুরছানার টিকার সময়সূচি: সম্পূর্ণ গাইড', 'assets/img1.jpg'],
            ['signs-your-cat-needs-a-vet', '7 Signs Your Cat Needs a Vet Visit', '৭টি লক্ষণ যে আপনার বিড়ালের ডাক্তার দেখানো দরকার', 'assets/img6.jpg'],
            ['pet-safety-in-summer-heat', 'Keeping Your Pet Safe in Summer Heat', 'গরমে পোষা প্রাণীকে নিরাপদ রাখার উপায়', 'assets/img5.jpg'],
            ['dental-care-for-dogs', 'Dental Care for Dogs: Why It Matters', 'কুকুরের দাঁতের যত্ন কেন জরুরি', 'assets/img4.jpg'],
            ['deworming-guide', 'Deworming & Tick Control Made Simple', 'কৃমি ও এঁটুলি নিয়ন্ত্রণ সহজ উপায়ে', 'assets/img7.jpg'],
            ['first-week-with-a-kitten', 'Your First Week With a New Kitten', 'নতুন বিড়ালছানার প্রথম সপ্তাহ', 'assets/img8.jpg'],
        ];
        foreach ($posts as $i => [$slug, $ten, $tbn, $img]) {
            Post::create([
                'slug' => $slug, 'title_en' => $ten, 'title_bn' => $tbn,
                'category_en' => 'Pet Care', 'category_bn' => 'পেট কেয়ার', 'image' => $img,
                'excerpt_en' => 'Practical, vet-approved advice from the PetCare Clinic team to keep your pet healthy and happy.',
                'excerpt_bn' => 'আপনার পোষা প্রাণীকে সুস্থ ও আনন্দে রাখতে পেটকেয়ার ক্লিনিকের ডাক্তারদের বাস্তবসম্মত পরামর্শ।',
                'body_en' => '<p>Regular check-ups, timely vaccines and a balanced diet are the foundation of a long, healthy life for your pet.</p><h3>What you should do</h3><p>Keep a record of every vaccine and visit, watch for changes in appetite or behaviour, and book a check-up at least twice a year.</p><p>If you notice anything unusual, call our 24/7 line or book an appointment online.</p>',
                'body_bn' => '<p>নিয়মিত চেকআপ, সময়মতো টিকা ও সুষম খাবার আপনার পোষা প্রাণীর দীর্ঘ ও সুস্থ জীবনের মূল ভিত্তি।</p><h3>আপনার করণীয়</h3><p>প্রতিটি টিকা ও ভিজিটের রেকর্ড রাখুন, খাওয়া বা আচরণের পরিবর্তন খেয়াল করুন এবং বছরে অন্তত দুবার চেকআপ করান।</p><p>অস্বাভাবিক কিছু দেখলে আমাদের ২৪/৭ লাইনে কল করুন অথবা অনলাইনে অ্যাপয়েন্টমেন্ট নিন।</p>',
                'published_at' => now()->subDays($i * 6 + 1),
            ]);
        }

        Faq::truncate();
        $faqs = [
            ['Do I need an appointment?', 'অ্যাপয়েন্টমেন্ট কি লাগবে?', 'Walk-ins are welcome, but booking online saves waiting time. Emergencies are seen immediately.', 'ওয়াক-ইন গ্রহণযোগ্য, তবে অনলাইনে বুক করলে অপেক্ষা কমে। জরুরি রোগী সাথে সাথে দেখা হয়।'],
            ['Can I book without paying online?', 'অনলাইনে পেমেন্ট ছাড়া বুক করা যাবে?', 'Yes. Online payment is optional; you can pay at the clinic.', 'হ্যাঁ। অনলাইন পেমেন্ট ঐচ্ছিক, ক্লিনিকেও পরিশোধ করতে পারবেন।'],
            ['Which payment methods do you accept?', 'কোন কোন পেমেন্ট মাধ্যম?', 'bKash, Nagad, Rocket, Visa/Mastercard and cash.', 'বিকাশ, নগদ, রকেট, ভিসা/মাস্টারকার্ড ও নগদ টাকা।'],
            ['Do you treat birds and exotic pets?', 'পাখি ও বিশেষ প্রাণীর চিকিৎসা হয়?', 'Yes, we treat birds, rabbits, guinea pigs and more.', 'হ্যাঁ, পাখি, খরগোশ, গিনিপিগসহ আরও অনেক প্রাণী।'],
            ['How do I cancel or reschedule?', 'কীভাবে বাতিল বা পরিবর্তন করব?', 'From your dashboard or by calling us at least 2 hours before.', 'ড্যাশবোর্ড থেকে বা কমপক্ষে ২ ঘণ্টা আগে ফোন করে।'],
            ['Are you open on Fridays?', 'শুক্রবার কি খোলা থাকে?', 'Our emergency unit is open 24/7, including Fridays. Selected doctors also hold Friday sessions.', 'আমাদের জরুরি বিভাগ শুক্রবারসহ ২৪/৭ খোলা। কয়েকজন ডাক্তার শুক্রবারেও বসেন।'],
            ['Do you offer home visits?', 'হোম ভিজিট সেবা আছে?', 'Yes, choose "Home visit" as the visit type when booking. Available within Dhaka city.', 'হ্যাঁ, বুকিংয়ের সময় ভিজিট ধরন "হোম ভিজিট" বেছে নিন। ঢাকা শহরের মধ্যে প্রযোজ্য।'],
        ];
        foreach ($faqs as $i => [$qen, $qbn, $aen, $abn]) {
            Faq::create(['question_en' => $qen, 'question_bn' => $qbn, 'answer_en' => $aen, 'answer_bn' => $abn, 'sort' => $i + 1]);
        }

        GalleryImage::truncate();
        foreach ([1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 3, 12] as $i => $n) {
            GalleryImage::create(['image' => "assets/img{$n}.jpg", 'caption_en' => 'PetCare Clinic', 'caption_bn' => 'পেটকেয়ার ক্লিনিক', 'sort' => $i + 1]);
        }

        Testimonial::truncate();
        foreach ([
            ['Sadia Islam', 'Owner of Mini', 'মিনির মালিক', 'My cat recovered so fast after surgery. The doctors were so caring and kept me updated.', 'সার্জারির পর আমার বিড়াল খুব দ্রুত সুস্থ হয়েছে। ডাক্তাররা খুব যত্নশীল ছিলেন।', 'assets/img6.jpg'],
            ['Rafiq Ahmed', 'Owner of Bruno', 'ব্রুনোর মালিক', 'Booking online took a minute and there was no waiting at the clinic. Highly recommended!', 'অনলাইনে বুকিং করতে এক মিনিট লেগেছে, ক্লিনিকে অপেক্ষা করতে হয়নি।', 'assets/img8.jpg'],
            ['Nadia Karim', 'Owner of Luna', 'লুনার মালিক', 'Their emergency team saved my dog at 2 AM. Forever grateful.', 'রাত ২টায় তাদের জরুরি দল আমার কুকুরকে বাঁচিয়েছে।', 'assets/img7.jpg'],
        ] as [$name, $sen, $sbn, $ten, $tbn, $photo]) {
            Testimonial::create(['name' => $name, 'subtitle_en' => $sen, 'subtitle_bn' => $sbn, 'text_en' => $ten, 'text_bn' => $tbn, 'rating' => 5, 'photo' => $photo]);
        }
    }
}
