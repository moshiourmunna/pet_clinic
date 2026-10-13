<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['payload'=>'array'];
    }

    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function user() { return $this->belongsTo(User::class); }
}
