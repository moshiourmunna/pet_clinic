<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id'];

    protected array $translatable = ['name','title','degree','bio'];

    protected function casts(): array
    {
        return ['is_active'=>'boolean','rating'=>'float'];
    }

    public const SPECIALITIES = ['surgery' => 'Surgery', 'medicine' => 'Medicine', 'dermatology' => 'Dermatology', 'emergency' => 'Emergency', 'exotic' => 'Exotic'];
    public function getRouteKeyName(): string { return 'slug'; }
    public function schedules() { return $this->hasMany(DoctorSchedule::class)->orderBy('day_of_week')->orderBy('start_time'); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function scopeActive($q) { return $q->where('is_active', true)->orderBy('sort'); }
    public function getPhotoUrlAttribute(): string
    {
        $p = $this->attributes['photo'] ?? null;
        if (! $p) return asset('assets/img3.jpg');
        return str_starts_with($p, 'assets/') ? asset($p) : asset('storage/'.$p);
    }
    public function worksOn(int $dow): bool { return $this->schedules->contains('day_of_week', $dow); }
}
