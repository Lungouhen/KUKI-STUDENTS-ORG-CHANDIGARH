<?php

namespace App\Support;

use App\Models\Setting;

class SiteBrand
{
    public const DEFAULT_NAME = "KUKI STUDENTS' ORGANISATION CHANDIGARH";
    public const DEFAULT_ABBREVIATION = 'KSO CHANDIGARH';
    public const DEFAULT_TAGLINE = 'Empowering Students • Preserving Culture • Serving Community';
    public const DEFAULT_ANNOUNCEMENT = '📢 Welcome to KSO Chandigarh! Annual Membership Registration 2025-2026 is now OPEN. Get your official digital student ID card online!';
    public const DEFAULT_PRIMARY_COLOR = '#003566';
    public const DEFAULT_ACCENT_COLOR = '#0d9488';
    public const DEFAULT_EMAIL = 'ksochandigarh@gmail.com';
    public const DEFAULT_PHONE = '+91 98765 43210';
    public const DEFAULT_HELPLINE = '+91 98765 43211';
    public const DEFAULT_ADDRESS = 'Room 12, Student Centre, Panjab University, Sector 14, Chandigarh, 160014';
    public const DEFAULT_WHATSAPP = '+919876543210';
    public const DEFAULT_FACEBOOK = 'https://facebook.com/ksochandigarh';
    public const DEFAULT_INSTAGRAM = 'https://instagram.com/kso_chandigarh';
    public const DEFAULT_MAP_EMBED_URL = 'https://www.google.com/maps?q=Chandigarh&output=embed';

    protected static function resolve(string $key, mixed $default): string
    {
        $value = Setting::get($key, $default);

        return is_string($value) ? trim($value) ?: $default : (string) $value ?: $default;
    }

    public static function name(): string
    {
        return self::resolve('siteName', self::DEFAULT_NAME);
    }

    public static function abbreviation(): string
    {
        return self::resolve('abbreviation', self::DEFAULT_ABBREVIATION);
    }

    public static function tagline(): string
    {
        return self::resolve('tagline', self::DEFAULT_TAGLINE);
    }

    public static function announcement(): string
    {
        return self::resolve('announcement', self::DEFAULT_ANNOUNCEMENT);
    }

    public static function primaryColor(): string
    {
        return self::resolve('primaryColor', self::DEFAULT_PRIMARY_COLOR);
    }

    public static function accentColor(): string
    {
        return self::resolve('accentColor', self::DEFAULT_ACCENT_COLOR);
    }

    public static function email(): string
    {
        return self::resolve('email', self::DEFAULT_EMAIL);
    }

    public static function phone(): string
    {
        return self::resolve('phone', self::DEFAULT_PHONE);
    }

    public static function helpline(): string
    {
        return self::resolve('helpline', self::DEFAULT_HELPLINE);
    }

    public static function address(): string
    {
        return self::resolve('address', self::DEFAULT_ADDRESS);
    }

    public static function whatsapp(): string
    {
        return self::resolve('whatsapp', self::DEFAULT_WHATSAPP);
    }

    public static function facebook(): string
    {
        return self::resolve('facebook', self::DEFAULT_FACEBOOK);
    }

    public static function instagram(): string
    {
        return self::resolve('instagram', self::DEFAULT_INSTAGRAM);
    }

    public static function mapEmbedUrl(): string
    {
        return self::resolve('mapEmbedUrl', self::DEFAULT_MAP_EMBED_URL);
    }
}
