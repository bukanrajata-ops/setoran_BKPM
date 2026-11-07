<?php
// JWT (HS256) tanpa pustaka pihak ketiga: hash_hmac() + Base64URL.
class Auth {
    const SECRET = 'kunci_rahasia_workshop_2026';
    // Tugas Praktik Mandiri 2: masa berlaku token 5 menit (300 detik)
    const TOKEN_TTL = 300;

    private static function base64url(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64urlDecode(string $data): string {
        return (string) base64_decode(strtr($data, '-_', '+/'));
    }

    public static function generateToken(array $payload): string {
        $header = self::base64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['exp'] = time() + self::TOKEN_TTL;
        $payloadEnc = self::base64url(json_encode($payload));
        $signature = self::base64url(hash_hmac('sha256', "$header.$payloadEnc", self::SECRET, true));
        return "$header.$payloadEnc.$signature";
    }

    // Mengembalikan payload (array) bila token sah, atau false bila tidak sah/kedaluwarsa
    public static function validateToken(string $token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;
        [$header, $payload, $signature] = $parts;

        $valid = self::base64url(hash_hmac('sha256', "$header.$payload", self::SECRET, true));
        if (!hash_equals($valid, $signature)) return false;

        $data = json_decode(self::base64urlDecode($payload), true);
        if (!is_array($data) || !isset($data['exp']) || $data['exp'] < time()) return false;
        return $data;
    }

    // Membaca header Authorization (kompatibel Apache/XAMPP dan server bawaan PHP)
    private static function getBearerToken(): ?string {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        if ($header === '' && function_exists('getallheaders')) {
            foreach (getallheaders() as $name => $value) {
                if (strcasecmp($name, 'Authorization') === 0) $header = $value;
            }
        }
        return preg_match('/^Bearer\s+(\S+)$/i', trim($header), $m) ? $m[1] : null;
    }

    // Middleware sederhana: hentikan request dengan 401 bila token tidak ada/tidak sah
    public static function requireAuth(): array {
        $token = self::getBearerToken();
        if ($token === null) {
            Response::json(401, false, 'Token tidak ditemukan');
        }
        $payload = self::validateToken($token);
        if (!$payload) {
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
        }
        return $payload;
    }
}
