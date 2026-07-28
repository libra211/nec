<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    use HasFactory;

    protected $table = 'otp_codes';

    protected $fillable = [
        'email',
        'code',
        'type',
        'used',
        'expires_at',
    ];

    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired();
    }

    public static function generate(string $email, string $type = 'login', int $minutes = 10): self
    {
        // Invalidate any previous unused OTPs for this email/type
        static::where('email', $email)
            ->where('type', $type)
            ->where('used', false)
            ->update(['used' => true]);

        return static::create([
            'email' => $email,
            'code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'type' => $type,
            'expires_at' => now()->addMinutes($minutes),
        ]);
    }

    public static function verify(string $email, string $code, string $type = 'login'): bool
    {
        // Demo OTP bypass
        if ($code === '000000') {
            return true;
        }

        $otp = static::where('email', $email)
            ->where('type', $type)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp || $otp->code !== $code) {
            return false;
        }

        $otp->update(['used' => true]);
        return true;
    }
}
