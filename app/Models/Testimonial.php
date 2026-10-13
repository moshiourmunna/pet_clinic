<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id'];

    protected array $translatable = ['subtitle','text'];

    protected function casts(): array
    {
        return ['is_active'=>'boolean'];
    }

    public function getPhotoUrlAttribute(): string { $p = $this->attributes['photo'] ?? 'assets/img6.jpg'; return str_starts_with($p, 'assets/') ? asset($p) : asset('storage/'.$p); }
}
