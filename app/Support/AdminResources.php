<?php

namespace App\Support;

use App\Models;

/**
 * Field definitions for the admin content manager. Field types:
 * text, textarea, number, checkbox, select, image, lines (one item per line → JSON array), datetime.
 * Bilingual fields are declared as name_en / name_bn pairs; leave one empty and the other is shown.
 */
class AdminResources
{
    public static function all(): array
    {
        return [
            'services' => [
                'model' => Models\Service::class, 'label' => 'Services', 'icon' => 'fa-briefcase-medical', 'order' => 'sort',
                'columns' => ['name' => 'Name', 'price' => 'Price ৳', 'is_active' => 'Active'],
                'fields' => [
                    'name_en' => ['text', 'Name (English)'], 'name_bn' => ['text', 'Name (বাংলা)'],
                    'description_en' => ['textarea', 'Description (English)'], 'description_bn' => ['textarea', 'Description (বাংলা)'],
                    'slug' => ['text', 'Slug', 'required'], 'icon' => ['text', 'Font Awesome icon (e.g. fa-syringe)', 'required'],
                    'price' => ['number', 'Starting price (৳)', 'required'],
                    'price_unit_en' => ['text', 'Price unit (English, e.g. /day)'], 'price_unit_bn' => ['text', 'Price unit (বাংলা)'],
                    'sort' => ['number', 'Sort order'], 'is_featured' => ['checkbox', 'Show on home page'], 'is_active' => ['checkbox', 'Active'],
                ],
                'one_of' => ['name'],
            ],
            'doctors' => [
                'model' => Models\Doctor::class, 'label' => 'Doctors', 'icon' => 'fa-user-doctor', 'order' => 'sort',
                'columns' => ['name' => 'Name', 'title' => 'Speciality', 'fee' => 'Fee ৳', 'is_active' => 'Active'],
                'fields' => [
                    'name_en' => ['text', 'Name (English)'], 'name_bn' => ['text', 'Name (বাংলা)'],
                    'title_en' => ['text', 'Speciality title (English)'], 'title_bn' => ['text', 'Speciality title (বাংলা)'],
                    'degree_en' => ['text', 'Degrees (English)'], 'degree_bn' => ['text', 'Degrees (বাংলা)'],
                    'bio_en' => ['textarea', 'Bio (English)'], 'bio_bn' => ['textarea', 'Bio (বাংলা)'],
                    'slug' => ['text', 'Slug', 'required'],
                    'speciality' => ['select', 'Filter category', 'required', Models\Doctor::SPECIALITIES],
                    'experience' => ['number', 'Experience (years)', 'required'], 'fee' => ['number', 'Consultation fee (৳)', 'required'],
                    'rating' => ['number', 'Rating (0-5)'], 'reviews' => ['number', 'Review count'], 'patients' => ['number', 'Patients treated'],
                    'bvc_reg' => ['text', 'BVC registration no.'], 'photo' => ['image', 'Photo'],
                    'sort' => ['number', 'Sort order'], 'is_active' => ['checkbox', 'Active'],
                ],
                'one_of' => ['name'],
            ],
            'plans' => [
                'model' => Models\Plan::class, 'label' => 'Pricing Plans', 'icon' => 'fa-tags', 'order' => 'sort',
                'columns' => ['name' => 'Name', 'price' => 'Price ৳', 'is_featured' => 'Popular'],
                'fields' => [
                    'name_en' => ['text', 'Name (English)'], 'name_bn' => ['text', 'Name (বাংলা)'],
                    'price' => ['number', 'Price (৳)', 'required'],
                    'period_en' => ['text', 'Period (English, e.g. year)'], 'period_bn' => ['text', 'Period (বাংলা)'],
                    'features_en' => ['lines', 'Features (English, one per line)'], 'features_bn' => ['lines', 'Features (বাংলা, one per line)'],
                    'sort' => ['number', 'Sort order'], 'is_featured' => ['checkbox', 'Mark as popular'],
                ],
                'one_of' => ['name'],
            ],
            'posts' => [
                'model' => Models\Post::class, 'label' => 'Blog Posts', 'icon' => 'fa-newspaper', 'order' => 'published_at', 'dir' => 'desc',
                'columns' => ['title' => 'Title', 'category' => 'Category', 'published_at' => 'Published'],
                'fields' => [
                    'title_en' => ['text', 'Title (English)'], 'title_bn' => ['text', 'Title (বাংলা)'],
                    'category_en' => ['text', 'Category (English)'], 'category_bn' => ['text', 'Category (বাংলা)'],
                    'excerpt_en' => ['textarea', 'Excerpt (English)'], 'excerpt_bn' => ['textarea', 'Excerpt (বাংলা)'],
                    'body_en' => ['textarea', 'Body (English, HTML allowed)'], 'body_bn' => ['textarea', 'Body (বাংলা, HTML allowed)'],
                    'slug' => ['text', 'Slug', 'required'], 'image' => ['image', 'Cover image'],
                    'published_at' => ['datetime', 'Publish at (empty = draft)'],
                ],
                'one_of' => ['title'],
            ],
            'faqs' => [
                'model' => Models\Faq::class, 'label' => 'FAQ', 'icon' => 'fa-circle-question', 'order' => 'sort',
                'columns' => ['question' => 'Question', 'sort' => 'Sort'],
                'fields' => [
                    'question_en' => ['text', 'Question (English)'], 'question_bn' => ['text', 'Question (বাংলা)'],
                    'answer_en' => ['textarea', 'Answer (English)'], 'answer_bn' => ['textarea', 'Answer (বাংলা)'],
                    'sort' => ['number', 'Sort order'],
                ],
                'one_of' => ['question', 'answer'],
            ],
            'gallery' => [
                'model' => Models\GalleryImage::class, 'label' => 'Gallery', 'icon' => 'fa-images', 'order' => 'sort',
                'columns' => ['image' => 'Image', 'caption' => 'Caption', 'sort' => 'Sort'],
                'fields' => [
                    'image' => ['image', 'Image', 'required'],
                    'caption_en' => ['text', 'Caption (English)'], 'caption_bn' => ['text', 'Caption (বাংলা)'],
                    'sort' => ['number', 'Sort order'],
                ],
                'one_of' => [],
            ],
            'testimonials' => [
                'model' => Models\Testimonial::class, 'label' => 'Testimonials', 'icon' => 'fa-comment-dots', 'order' => 'id', 'dir' => 'desc',
                'columns' => ['name' => 'Name', 'subtitle' => 'Subtitle', 'rating' => 'Rating', 'is_active' => 'Active'],
                'fields' => [
                    'name' => ['text', 'Name', 'required'],
                    'subtitle_en' => ['text', 'Subtitle (English, e.g. Owner of Bruno)'], 'subtitle_bn' => ['text', 'Subtitle (বাংলা)'],
                    'text_en' => ['textarea', 'Review (English)'], 'text_bn' => ['textarea', 'Review (বাংলা)'],
                    'rating' => ['number', 'Rating (1-5)', 'required'], 'photo' => ['image', 'Photo'], 'is_active' => ['checkbox', 'Active'],
                ],
                'one_of' => ['text'],
            ],
        ];
    }

    public static function get(string $key): array
    {
        return self::all()[$key] ?? abort(404);
    }
}
