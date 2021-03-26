<?php

namespace App\Utils;

class Transformer
{
    public static function skeleton(bool $ok, string $message, $data, $withoutData = false)
    {
        $skeleton = [
            'ok' => $ok,
            'message' => $message,
        ];

        if (!$withoutData) {
            $skeleton['data'] = $data;
        }

        return $skeleton;
    }

    public static function success(string $message, $data = null, int $status = 200, array $headers = [])
    {
        return response()->json(self::skeleton(true, $message, $data), $status, $headers);
    }

    public static function failed(string $message, $data = null, int $status = 500, array $headers = [])
    {
        return response()->json(self::skeleton(false, $message, $data), $status, $headers);
    }
}
