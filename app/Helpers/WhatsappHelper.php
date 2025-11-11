<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class WhatsappHelper
{
    public static function kirimPesan($nomor, $pesan)
    {
        $token = env('FONNTE_TOKEN');
        $url = 'https://api.fonnte.com/send';

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->asForm()->post($url, [
            'target' => $nomor,
            'message' => $pesan,
        ]);

        return $response->json();
    }
}
