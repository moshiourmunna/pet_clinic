<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['date'=>'date'];
    }

    public const STATUSES = ['requested','confirmed','completed','cancelled'];
    public const PAYMENT_METHODS = ['clinic' => 'Pay at Clinic', 'bkash_nagad' => 'bKash / Nagad', 'card' => 'Card / SSLCommerz'];
    public const VISIT_TYPES = ['clinic' => 'In-clinic', 'online' => 'Online video', 'home' => 'Home visit'];
    public function user() { return $this->belongsTo(User::class); }
    public function pet() { return $this->belongsTo(Pet::class); }
    public function service() { return $this->belongsTo(Service::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public static function slotKey(int $doctorId, string $date, string $time): string { return $doctorId.'|'.$date.'|'.substr($time, 0, 5); }
    public function getTimeLabelAttribute(): ?string { return $this->time ? \Carbon\Carbon::parse($this->time)->format('g:i A') : null; }
    public function isUpcoming(): bool { return in_array($this->status, ['requested','confirmed']) && $this->date->gte(today()); }
    public function statusClass(): string { return ['confirmed'=>'ok','completed'=>'ok','cancelled'=>'bad'][$this->status] ?? 'wait'; }
    public function cancel(): void { $this->update(['status' => 'cancelled', 'slot_key' => null]); }
}
