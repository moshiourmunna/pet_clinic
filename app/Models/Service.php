<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id'];

    protected array $translatable = ['name','description','price_unit'];

    protected function casts(): array
    {
        return ['is_featured'=>'boolean','is_active'=>'boolean'];
    }

    public function getRouteKeyName(): string { return 'slug'; }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function scopeActive($q) { return $q->where('is_active', true)->orderBy('sort'); }
}
