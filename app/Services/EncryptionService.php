<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class EncryptionService
{
    // Fungsi untuk mengenkripsi string
    public static function encrypt($string)
    {
        // Menggunakan enkripsi AES-256-CBC dengan penambahan random string untuk keamanan ekstra
        $key = config('app.key'); // Menggunakan key dari Laravel configuration
        $iv = Str::random(16);  // IV acak (Initialization Vector)
        
        // Enkripsi string dengan IV dan key yang ada
        $encrypted = openssl_encrypt($string, 'AES-256-CBC', $key, 0, $iv);

        // Gabungkan IV dan hasil enkripsi ke dalam satu string dan encode dengan Base64
        $result = base64_encode($iv . $encrypted);

        return $result;
    }

    // Fungsi untuk mendekripsi string
    public static function decrypt($encryptedString)
    {
        // Decode hasil enkripsi yang telah di-Base64
        $decoded = base64_decode($encryptedString);

        // Pisahkan IV dan hasil enkripsi
        $iv = substr($decoded, 0, 16); // Ambil IV yang pertama (16 byte)
        $encrypted = substr($decoded, 16); // Sisanya adalah hasil enkripsi

        // Dekripsi menggunakan IV yang sama dan key dari Laravel config
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', config('app.key'), 0, $iv);

        return $decrypted;
    }
}
