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
 * Uploads go through postFile() (multipart/form-data, CURLFile). The local
 * source paths sent are tracked in sentFiles(); the file the server actually
 * serves afterwards comes back in the response body (e.g. `data.comprobante`
 * = `/uploads/...`), and ApiTestCase removes any new file under
 * public/uploads after every test.
 */
final class ApiClient
{
    /** @var list<string> local paths of every file sent via postFile() */
    private array $sentFiles = [];

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
     * POST multipart/form-data (file uploads, e.g. the pagomovil comprobante).
     *
     * The Content-Type header (with the boundary) is left to libcurl.
     *
     * @param array<string, mixed> $fields simple form fields (scalars)
     * @param array<string, string|array{path: string, mime?: string, name?: string}> $files
     *        field name => local file path (uploaded under its basename), or a
     *        spec to override the MIME type and/or the posted file name
     *        (used to send a file with a deliberately bad extension).
     * @return array{status: int, body: array|string, contentType: string}
     */
    public function postFile(string $path, array $fields = [], array $files = [], ?string $token = null): array
    {
        $multipart = [];
        foreach ($fields as $name => $value) {
            $multipart[(string) $name] = $value;
        }
        foreach ($files as $name => $spec) {
            if (is_array($spec)) {
                $file = new \CURLFile($spec['path'], $spec['mime'] ?? null, $spec['name'] ?? null);
                $this->sentFiles[] = $spec['path'];
            } else {
                $file = new \CURLFile($spec);
                $this->sentFiles[] = $spec;
            }
            $multipart[(string) $name] = $file;
        }

        return $this->send('POST', $path, $multipart, $token, multipart: true);
    }

    /**
     * Local paths of the files uploaded through postFile() so far
     * (the served copy lives under public/uploads and is cleaned up by
     * ApiTestCase, not here).
     *
     * @return list<string>
     */
    public function sentFiles(): array
    {
        return $this->sentFiles;
    }

    /**
     * @param array|null $json payload encoded as JSON when not null
     * @return array{status: int, body: array|string, contentType: string}
     */
    private function request(string $method, string $path, ?array $json, ?string $token): array
    {
        return $this->send($method, $path, $json, $token, multipart: false);
    }

    /**
     * @param array|null $payload JSON payload (array) or multipart parts when
     *        $multipart; null for a bodyless request
     * @return array{status: int, body: array|string, contentType: string}
     */
    private function send(string $method, string $path, ?array $payload, ?string $token, bool $multipart): array
    {
        $ch = curl_init($this->baseUrl . $path);
        if ($ch === false) {
            throw new \RuntimeException('curl_init failed.');
        }

        $contentType = '';

        $headers = ['Accept: application/json'];
        if ($payload !== null && !$multipart) {
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

        if ($payload !== null) {
            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                $multipart ? $payload : json_encode($payload, JSON_UNESCAPED_SLASHES)
            );
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
