<?php
class BrevoService
{
    private const API_BASE = 'https://api.brevo.com/v3';
    private string $apiKey;
    private int $templateId;
    private int $listId;

    public function __construct()
    {
        $this->apiKey = $_ENV['BREVO_API_KEY'] ?? '';
        $this->templateId = (int) ($_ENV['BREVO_TEMPLATE_ID'] ?? 0);
        $this->listId = (int) ($_ENV['BREVO_LIST_ID'] ?? 0);
    }

    public function envoyerNewsletter(string $titre, string $chapeau, string $slug, string $image): void
    {
        if (!$this->apiKey || !$this->templateId || !$this->listId) {
            error_log('[Brevo] Configuration incomplète — newsletter non envoyée.');
            return;
        }

        $contacts = $this->getContacts();
        if (empty($contacts)) {
            error_log('[Brevo] Aucun contact dans la liste ' . $this->listId);
            return;
        }

        $base = rtrim($_ENV['APP_URL'] ?? 'https://lacerise.blog', '/');
        $lien = $base . '/article/' . $slug;
        $imgUrl = $image ? $base . '/assets/images/' . $image : '';

        // Un email individuel par contact
        foreach ($contacts as $contact) {
            $this->request('POST', '/smtp/email', [
                'templateId' => $this->templateId,
                'to' => [['email' => $contact['email']]],
                'params' => [
                    'titre' => $titre,
                    'chapeau' => $chapeau,
                    'lien' => $lien,
                    'image' => $imgUrl,
                ],
            ]);
        }

        error_log('[Brevo] Newsletter envoyée à ' . count($contacts) . ' contacts.');
    }

    private function getContacts(): array
    {
        $result = $this->request('GET', '/contacts/lists/' . $this->listId . '/contacts?limit=500');
        $contacts = [];
        foreach ($result['contacts'] ?? [] as $contact) {
            if (!empty($contact['email'])) {
                $contacts[] = ['email' => $contact['email']];
            }
        }
        return $contacts;
    }

    private function request(string $method, string $endpoint, array $data = []): array
    {
        $ch = curl_init(self::API_BASE . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'content-type: application/json',
                'api-key: ' . $this->apiKey,
            ],
        ]);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($body === false) {
            error_log('[Brevo] Erreur cURL sur ' . $endpoint);
            return [];
        }
        $decoded = json_decode($body, true) ?? [];
        if ($httpCode >= 400) {
            error_log('[Brevo] Erreur API ' . $httpCode . ' (' . $endpoint . ') : ' . $body);
        }
        return $decoded;
    }
}