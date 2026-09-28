<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Minimal black-box HTTP client for the JSON API.
 *
 * Every response is normalized to an object/array with:
 *  - status (int): HTTP status code
 *  - body (array|string): decoded JSON array, or the raw string when the
 *    response is not JSON
 *  - contentType (string): response Content-Type header
 *
 * TODO(phase 3): multipart form upload (payments/pagomovil comprobante).
 */
final class ApiClient
{
    public function __construct(private readonly string $baseUrl)
    {
    }

    /**
     * @return array{status: int, body: array|string, contentType: string}
     */
    public function get(string $path, ?string $token = null): array
    {
        return $this->request('GET', $path, null, $token);
    }

    /**
     * @return array{status: int, body: array|string, contentType: string}
     */
    public function post(string $path, array $json = [], ?string $token = null): array
    {
        return $this->request('POST', $path, $json, $token);
    }

    /**
     * @return array{status: int, body: array|string, contentType: string}
     */
    public function put(string $path, array $json = [], ?string $token = null): array
    {
        return $this->request('PUT', $path, $json, $token);
    }

    /**
     * @return array{status: int, body: array|string, contentType: string}
     */
    public function delete(string $path, ?string $token = null): array
    {
        return $this->request('DELETE', $path, null, $token);
    }

    /**
     * @param array|null $json payload encoded as JSON when not null
     * @return array{status: int, body: array|string, contentType: string}
     */
    private function request(string $method, string $path, ?array $json, ?string $token): array
    {
        $ch = curl_init($this->baseUrl . $path);
        if ($ch === false) {
            throw new \RuntimeException('curl_init failed.');
        }

        $contentType = '';

        $headers = ['Accept: application/json'];
        if ($json !== null) {
            $headers[] = 'Content-Type: application/json';
        }
        if ($token !== null) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HEADERFUNCTION => static function ($ch, string $line) use (&$contentType): int {
                $length = strlen($line);
                if (stripos($line, 'Content-Type:') === 0) {
                    $contentType = trim(substr($line, strlen('Content-Type:')));
                }

                return $length;
            },
        ]);

        if ($json !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json, JSON_UNESCAPED_SLASHES));
        }

        $raw = curl_exec($ch);
        if ($raw === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException("HTTP {$method} {$path} failed: {$error}");
        }

        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        $rawBody = (string) $raw;

        $decoded = json_decode($rawBody, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $body = $decoded;
        } else {
            $body = $rawBody;
        }

        return [
            'status' => $status,
            'body' => $body,
            'contentType' => $contentType,
        ];
    }
}
