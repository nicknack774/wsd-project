<?php

namespace App\Support;

class Base62
{
    private const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function encode(int $number): string
    {
        if ($number === 0) {
            return '0';
        }

        $base = strlen(self::ALPHABET);
        $result = '';

        while ($number > 0) {
            $result = self::ALPHABET[$number % $base] . $result;
            $number = intdiv($number, $base);
        }

        return $result;
    }
}
