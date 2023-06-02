<?php

namespace App\Helpers;

class Helper
{
    public static function randomStr(int $length): string
    {
        $string = '';

        while (($len = mb_strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= mb_substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    public static function randomInt(int $length): int
    {
        return rand(pow(10, $length - 1), pow(10, $length) - 1);
    }
}