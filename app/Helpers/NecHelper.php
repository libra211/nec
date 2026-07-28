<?php

namespace App\Helpers;

use App\Models\Sequence;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NecHelper
{
    public static function base_url($path = ''): string
    {
        $base = rtrim(config('app.url', ''), '/');
        return $path !== '' ? $base . '/' . ltrim($path, '/') : $base;
    }

    public static function asset_url($path): string
    {
        return self::base_url('assets/' . ltrim($path, '/'));
    }

    public static function e($str): string
    {
        return htmlspecialchars((string) $str, ENT_QUOTES, 'UTF-8');
    }

    public static function t($v): string
    {
        return $v === null || $v === '' ? '—' : (string) $v;
    }

    public static function genderLabel($g): string
    {
        $labels = ['M' => 'Male', 'F' => 'Female'];
        return $labels[strtoupper((string) $g)] ?? 'Other';
    }

    public static function genderIcon($g): string
    {
        $icons = ['M' => 'fa-mars', 'F' => 'fa-venus'];
        return $icons[strtoupper((string) $g)] ?? 'fa-genderless';
    }

    public static function computeAge($dob): int
    {
        if (!$dob) return 0;
        $date = $dob instanceof \Carbon\Carbon ? $dob : \Carbon\Carbon::parse($dob);
        return $date->age;
    }

    public static function statusBadge($s): string
    {
        $map = [
            'active' => 'success',
            'approved' => 'success',
            'published' => 'success',
            'pending' => 'warning',
            'submitted' => 'warning',
            'inactive' => 'secondary',
            'rejected' => 'danger',
            'suspended' => 'danger',
            'cancelled' => 'danger',
            'expired' => 'danger',
        ];
        $color = $map[strtolower((string) $s)] ?? 'secondary';
        return '<span class="badge badge-' . $color . '">' . self::e($s) . '</span>';
    }

    public static function timeAgoShort($dt): string
    {
        if (!$dt) return '';
        $carbon = $dt instanceof \Carbon\Carbon ? $dt : \Carbon\Carbon::parse($dt);
        $diff = $carbon->diffInSeconds(now());

        if ($diff < 60) return $diff . 's ago';
        if ($diff < 3600) return floor($diff / 60) . 'm ago';
        if ($diff < 86400) return floor($diff / 3600) . 'h ago';
        if ($diff < 604800) return floor($diff / 86400) . 'd ago';
        if ($diff < 2592000) return floor($diff / 604800) . 'w ago';
        return $carbon->diffForHumans();
    }

    public static function timeAgo($dt): string
    {
        if (!$dt) return '';
        $carbon = $dt instanceof \Carbon\Carbon ? $dt : \Carbon\Carbon::parse($dt);
        return $carbon->diffForHumans();
    }

    public static function setting_get($key, $default = null)
    {
        $setting = Setting::find($key);
        return $setting ? $setting->value : $default;
    }

    public static function setting_set($key, $value): bool
    {
        return (bool) Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function nec_encrypt($plaintext): string
    {
        return Crypt::encryptString((string) $plaintext);
    }

    public static function nec_decrypt($ciphertext): string
    {
        return Crypt::decryptString((string) $ciphertext);
    }

    public static function obfuscate_serial($id): string
    {
        $obfuscated = (($id * 456789) + 123457) % 1000000;
        return str_pad((string) $obfuscated, 6, '0', STR_PAD_LEFT);
    }

    public static function generate_voter_id($gender, $year): string
    {
        $genderCode = strtoupper((string) $gender) === 'F' ? 'F' : 'M';
        $shortYear = substr((string) $year, -2);

        $nextId = DB::transaction(function () use ($genderCode, $shortYear) {
            $seqName = 'NEC' . $shortYear . $genderCode;

            $seq = Sequence::where('seq_name', $seqName)->lockForUpdate()->first();
            if (!$seq) {
                $seq = Sequence::create(['seq_name' => $seqName, 'seq_value' => 1]);
                $nextId = 1;
            } else {
                $nextId = $seq->seq_value;
                $seq->increment('seq_value');
            }

            return $nextId;
        });

        $serial = self::obfuscate_serial($nextId);

        return 'NEC' . $shortYear . $genderCode . $serial;
    }
}

if (!function_exists('e')) {
    function e($str): string
    {
        return \App\Helpers\NecHelper::e($str);
    }
}

if (!function_exists('t')) {
    function t($v): string
    {
        return \App\Helpers\NecHelper::t($v);
    }
}

if (!function_exists('nec_encrypt')) {
    function nec_encrypt($plaintext): string
    {
        return \App\Helpers\NecHelper::nec_encrypt($plaintext);
    }
}

if (!function_exists('nec_decrypt')) {
    function nec_decrypt($ciphertext): string
    {
        return \App\Helpers\NecHelper::nec_decrypt($ciphertext);
    }
}
