<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id'];

    protected array $translatable = ['caption'];

    public function getUrlAttribute(): string { $p = $this->attributes['image']; return str_starts_with($p, 'assets/') ? asset($p) : asset('storage/'.$p); }
}
