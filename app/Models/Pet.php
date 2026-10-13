<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['weight'=>'float'];
    }

    public const SPECIES = ['Dog','Cat','Bird','Rabbit','Other'];
    public function user() { return $this->belongsTo(User::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function medicalRecords() { return $this->hasMany(MedicalRecord::class)->latest('visit_date'); }
    public function vaccinations() { return $this->hasMany(Vaccination::class)->orderBy('due_on'); }
}
