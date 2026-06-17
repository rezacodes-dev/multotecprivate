<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class MicrosoftGraphMailService
{
    protected string $tenantId;
    protected string $clientId;
    protected string $clientSecret;
    protected string $fromAddress;
    protected bool $saveToSentItems;

    public function __construct()
    {
        $this->tenantId = (string) config('services.microsoft_graph.tenant_id');
        $this->clientId = (string) config('services.microsoft_graph.client_id');
        $this->clientSecret = (string) config('services.microsoft_graph.client_secret');
        $this->fromAddress = (string) config('services.microsoft_graph.from_address');
        $this->saveToSentItems = (bool) config('services.microsoft_graph.save_to_sent_items', true);
    }

    public function sendMail(string $to, string $subject, string $body): array
    {
        $this->validateConfiguration();

        $token = $this->getAccessToken();

        $payload = [
            'message' => [
                'subject' => $subject,
                'body' => [
                    'contentType' => 'HTML',
                    'content' => $body,
                ],
                'toRecipients' => [
                    [
                        'emailAddress' => [
                            'address' => $to,
                        ],
                    ],
                ],
            ],
            'saveToSentItems' => $this->saveToSentItems,
        ];

        $sender = rawurlencode($this->fromAddress);
        $response = Http::withToken($token)
            ->acceptJson()
            ->post("https://graph.microsoft.com/v1.0/users/{$sender}/sendMail", $payload);

        if (!$response->successful()) {
            throw new RuntimeException('Microsoft Graph sendMail failed: '.$response->status().' '.$response->body());
        }

        return [
            'status' => $response->status(),
            'message' => 'Mail Sent Successfully',
        ];
    }

    protected function getAccessToken(): string
    {
        $response = Http::asForm()
            ->acceptJson()
            ->post("https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token", [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => 'https://graph.microsoft.com/.default',
                'grant_type' => 'client_credentials',
            ]);

        if (!$response->successful()) {
            throw new RuntimeException('Microsoft Graph token request failed: '.$response->status().' '.$response->body());
        }

        $token = $response->json('access_token');

        if (!$token) {
            throw new RuntimeException('Microsoft Graph token response did not include an access token.');
        }

        return $token;
    }

    protected function validateConfiguration(): void
    {
        $missing = [];

        foreach ([
            'MICROSOFT_GRAPH_TENANT_ID' => $this->tenantId,
            'MICROSOFT_GRAPH_CLIENT_ID' => $this->clientId,
            'MICROSOFT_GRAPH_CLIENT_SECRET' => $this->clientSecret,
            'MICROSOFT_GRAPH_FROM_ADDRESS' => $this->fromAddress,
        ] as $key => $value) {
            if (!$value) {
                $missing[] = $key;
            }
        }

        if ($missing) {
            throw new InvalidArgumentException('Missing Microsoft Graph mail configuration: '.implode(', ', $missing));
        }
    }
}
