<?php

namespace App\Support;

use App\Models\Setting;

class SiteBrand
{
    public const DEFAULT_NAME = "KUKI STUDENTS' ORGANISATION CHANDIGARH";
    public const DEFAULT_ABBREVIATION = 'KSO CHANDIGARH';
    public const DEFAULT_TAGLINE = 'Empowering Students • Preserving Culture • Serving Community';
    public const DEFAULT_ANNOUNCEMENT = '📢 Welcome to KSO Chandigarh! Annual Membership Registration 2025-2026 is now OPEN. Get your official digital student ID card online!';

    public static function name(): string
    {
        return trim((string) Setting::get('siteName', self::DEFAULT_NAME)) ?: self::DEFAULT_NAME;
    }

    public static function abbreviation(): string
    {
        return trim((string) Setting::get('abbreviation', self::DEFAULT_ABBREVIATION)) ?: self::DEFAULT_ABBREVIATION;
    }

    public static function tagline(): string
    {
        return trim((string) Setting::get('tagline', self::DEFAULT_TAGLINE)) ?: self::DEFAULT_TAGLINE;
    }

    public static function announcement(): string
    {
        return trim((string) Setting::get('announcement', self::DEFAULT_ANNOUNCEMENT)) ?: self::DEFAULT_ANNOUNCEMENT;
    }
}
