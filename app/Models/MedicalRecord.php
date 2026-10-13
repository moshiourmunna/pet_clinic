<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['visit_date'=>'date'];
    }

    public function pet() { return $this->belongsTo(Pet::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
}
