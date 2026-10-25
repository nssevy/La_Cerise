<?php
class GoogleAnalyticsService
{
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const API_BASE = 'https://analyticsdata.googleapis.com/v1beta';
    private const TOKEN_CACHE_FILE = __DIR__ . '/../../cache/ga_token.json';

    private string $propertyId;
    private string $clientEmail;
    private string $privateKey;

    public function __construct()
    {
        $this->propertyId = $_ENV['GA_PROPERTY_ID'] ?? '';
        $this->clientEmail = $_ENV['GA_SA_CLIENT_EMAIL'] ?? '';
        $this->privateKey = str_replace('\n', "\n", $_ENV['GA_SA_PRIVATE_KEY'] ?? '');
    }

    public function isConfigured(): bool
    {
        return (bool) ($this->propertyId && $this->clientEmail && $this->privateKey);
    }

    /**
     * Vue d'ensemble : visiteurs, vues, nouveaux vs récurrents, sur les N derniers jours.
     */
    public function getApercu(int $jours = 28): ?array
    {
        $result = $this->runReport([
            'dateRanges' => [['startDate' => $jours . 'daysAgo', 'endDate' => 'today']],
            'dimensions' => [['name' => 'newVsReturning']],
            'metrics' => [
                ['name' => 'activeUsers'],
                ['name' => 'screenPageViews'],
                ['name' => 'averageSessionDuration'],
            ],
        ]);

        if ($result === null) {
            return null;
        }

        $nouveaux = 0;
        $recurrents = 0;
        $vues = 0;
        $dureeTotale = 0.0;
        $lignes = 0;

        foreach ($result['rows'] ?? [] as $row) {
            $type = $row['dimensionValues'][0]['value'] ?? '';
            $users = (int) ($row['metricValues'][0]['value'] ?? 0);
            $pageViews = (int) ($row['metricValues'][1]['value'] ?? 0);
            $duree = (float) ($row['metricValues'][2]['value'] ?? 0);

            if ($type === 'new') {
                $nouveaux = $users;
            } elseif ($type === 'returning') {
                $recurrents = $users;
            }
            $vues += $pageViews;
            $dureeTotale += $duree;
            $lignes++;
        }

        return [
            'visiteurs' => $nouveaux + $recurrents,
            'nouveaux' => $nouveaux,
            'recurrents' => $recurrents,
            'vues' => $vues,
            'duree_moyenne_secondes' => $lignes ? round($dureeTotale / $lignes) : 0,
        ];
    }

    private function runReport(array $body): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        $ch = curl_init(self::API_BASE . '/properties/' . $this->propertyId . ':runReport');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ],
            CURLOPT_POSTFIELDS => json_encode($body),
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode >= 400) {
            error_log('[GoogleAnalytics] Erreur API ' . $httpCode . ' : ' . $response);
            return null;
        }

        return json_decode($response, true);
    }

    private function getAccessToken(): ?string
    {
        if (is_file(self::TOKEN_CACHE_FILE)) {
            $cached = json_decode(file_get_contents(self::TOKEN_CACHE_FILE), true);
            if (($cached['expires_at'] ?? 0) > time() + 60) {
                return $cached['access_token'];
            }
        }

        $jwt = $this->buildSignedJwt();
        if (!$jwt) {
            return null;
        }

        $ch = curl_init(self::TOKEN_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]),
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode >= 400) {
            error_log('[GoogleAnalytics] Erreur auth ' . $httpCode . ' : ' . $response);
            return null;
        }

        $data = json_decode($response, true);
        $accessToken = $data['access_token'] ?? null;
        if (!$accessToken) {
            return null;
        }

        @file_put_contents(self::TOKEN_CACHE_FILE, json_encode([
            'access_token' => $accessToken,
            'expires_at' => time() + (int) ($data['expires_in'] ?? 3600),
        ]));

        return $accessToken;
    }

    private function buildSignedJwt(): ?string
    {
        $now = time();
        $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $claims = $this->base64UrlEncode(json_encode([
            'iss' => $this->clientEmail,
            'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
            'aud' => self::TOKEN_URL,
            'exp' => $now + 3600,
            'iat' => $now,
        ]));

        $unsigned = $header . '.' . $claims;
        $signature = '';
        $ok = openssl_sign($unsigned, $signature, $this->privateKey, 'sha256WithRSAEncryption');

        if (!$ok) {
            error_log('[GoogleAnalytics] Échec signature JWT — clé privée invalide ?');
            return null;
        }

        return $unsigned . '.' . $this->base64UrlEncode($signature);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
