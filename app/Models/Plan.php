<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id'];

    protected array $translatable = ['name','period','features'];

    protected function casts(): array
    {
        return ['features_en'=>'array','features_bn'=>'array','is_featured'=>'boolean'];
    }

}
