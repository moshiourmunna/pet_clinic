<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id'];

    protected array $translatable = ['title','category','excerpt','body'];

    protected function casts(): array
    {
        return ['published_at'=>'datetime'];
    }

    public function getRouteKeyName(): string { return 'slug'; }
    public function scopePublished($q) { return $q->whereNotNull('published_at')->where('published_at', '<=', now())->latest('published_at'); }
    public function getImageUrlAttribute(): string { $p = $this->attributes['image'] ?? 'assets/img1.jpg'; return str_starts_with($p, 'assets/') ? asset($p) : asset('storage/'.$p); }
}
