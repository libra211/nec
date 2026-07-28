<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class SecurityAudit
{
    public static function auditLogin(string $email, bool $success): void
    {
        ActivityLog::create([
            'user_email' => $email,
            'action' => $success ? 'login_success' : 'login_failed',
            'entity_type' => 'Auth',
            'details' => $success ? "Successful login for {$email}" : "Failed login attempt for {$email}",
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }

    public static function checkBruteForce(string $email, int $maxAttempts = 5, int $windowMinutes = 15): bool
    {
        $attempts = DB::table('nec_activity_logs')
            ->where('user_email', $email)
            ->where('action', 'login_failed')
            ->where('created_at', '>=', now()->subMinutes($windowMinutes))
            ->count();

        return $attempts >= $maxAttempts;
    }

    public static function sanitizeOutput($data)
    {
        if (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }

        if (is_array($data)) {
            return array_map([self::class, 'sanitizeOutput'], $data);
        }

        if (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->{$key} = self::sanitizeOutput($value);
            }
        }

        return $data;
    }
}
