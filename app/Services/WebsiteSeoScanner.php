<?php

namespace App\Services;

use App\Rules\PublicHttpUrl;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Fetches a public website (SSRF-safe) and extracts the on-page SEO signals
 * used to brief the AI report generator.
 */
class WebsiteSeoScanner
{
    /**
     * Scan the given URL and return a structured array of SEO signals.
     *
     * @return array<string, mixed>
     *
     * @throws RuntimeException when the site cannot be safely reached or read.
     */
    public function scan(string $url): array
    {
        $response = $this->fetch($url);

        $contentType = strtolower((string) $response->header('Content-Type'));
        if ($contentType !== '' && ! str_contains($contentType, 'text/html')) {
            throw new RuntimeException('That address did not return a web page we can read.');
        }

        $finalUrl = $response->effectiveUri()?->__toString() ?? $url;
        $html = $this->cap($response->body());

        $signals = $this->extract($html, $finalUrl);
        $signals = array_merge($signals, $this->secondarySignals($finalUrl));

        return $signals;
    }

    /**
     * Fetch the URL, following redirects manually while re-validating every hop.
     *
     * @throws RuntimeException
     */
    private function fetch(string $url): Response
    {
        $config = config('seo-report.fetch');
        $maxRedirects = (int) $config['max_redirects'];

        $current = $url;

        for ($hop = 0; $hop <= $maxRedirects; $hop++) {
            if (! PublicHttpUrl::isSafe($current)) {
                throw new RuntimeException('That website address is not allowed.');
            }

            try {
                $response = Http::withoutRedirecting()
                    ->withHeaders(['User-Agent' => $config['user_agent']])
                    ->timeout((int) $config['timeout'])
                    ->connectTimeout((int) $config['connect_timeout'])
                    ->get($current);
            } catch (\Throwable) {
                throw new RuntimeException('We could not reach that website.');
            }

            if ($response->redirect()) {
                $location = $response->header('Location');
                if (blank($location)) {
                    break;
                }
                $current = $this->resolveLocation($current, $location);

                continue;
            }

            if ($response->failed()) {
                throw new RuntimeException('That website returned an error when we tried to load it.');
            }

            return $response;
        }

        throw new RuntimeException('That website redirected too many times.');
    }

    /**
     * Resolve a (possibly relative) Location header against the current URL.
     */
    private function resolveLocation(string $base, string $location): string
    {
        if (Str::startsWith($location, ['http://', 'https://'])) {
            return $location;
        }

        $parts = parse_url($base);
        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'] ?? '';
        $prefix = $scheme.'://'.$host;

        return $prefix.'/'.ltrim($location, '/');
    }

    /**
     * Truncate the response body to the configured byte cap.
     */
    private function cap(string $body): string
    {
        $max = (int) config('seo-report.fetch.max_bytes');

        return strlen($body) > $max ? substr($body, 0, $max) : $body;
    }

    /**
     * Extract on-page SEO signals from the HTML document.
     *
     * @return array<string, mixed>
     */
    private function extract(string $html, string $finalUrl): array
    {
        $document = new DOMDocument;
        libxml_use_internal_errors(true);
        // Prepend an encoding hint so libxml reads the markup as UTF-8.
        $document->loadHTML('<?xml encoding="utf-8" ?>'.$html);
        libxml_clear_errors();

        $xpath = new DOMXPath($document);

        $images = $xpath->query('//img') ?: [];
        $imagesWithAlt = 0;
        foreach ($images as $image) {
            if (filled($image->getAttribute('alt'))) {
                $imagesWithAlt++;
            }
        }
        $imageCount = count($images);

        $jsonLdTypes = [];
        foreach ($xpath->query("//script[@type='application/ld+json']") ?: [] as $node) {
            $decoded = json_decode($node->textContent, true);
            foreach ($this->collectSchemaTypes($decoded) as $type) {
                $jsonLdTypes[] = $type;
            }
        }

        $bodyText = $this->visibleText($document, $xpath);

        return [
            'final_url' => $finalUrl,
            'https' => str_starts_with(strtolower($finalUrl), 'https://'),
            'title' => $this->firstString($xpath, '//title'),
            'meta_description' => $this->firstString($xpath, "//meta[@name='description']/@content"),
            'meta_robots' => $this->firstString($xpath, "//meta[@name='robots']/@content"),
            'canonical' => $this->firstString($xpath, "//link[@rel='canonical']/@href"),
            'lang' => $this->firstString($xpath, '//html/@lang'),
            'viewport' => filled($this->firstString($xpath, "//meta[@name='viewport']/@content")),
            'h1_count' => $xpath->query('//h1')?->length ?? 0,
            'h2_count' => $xpath->query('//h2')?->length ?? 0,
            'h3_count' => $xpath->query('//h3')?->length ?? 0,
            'h1_text' => $this->firstString($xpath, '//h1'),
            'og_tags' => $this->metaGroup($xpath, "//meta[starts-with(@property,'og:')]", 'property'),
            'twitter_tags' => $this->metaGroup($xpath, "//meta[starts-with(@name,'twitter:')]", 'name'),
            'image_count' => $imageCount,
            'images_with_alt' => $imagesWithAlt,
            'image_alt_coverage' => $imageCount > 0 ? round($imagesWithAlt / $imageCount, 2) : null,
            'word_count' => str_word_count($bodyText),
            'schema_types' => array_values(array_unique($jsonLdTypes)),
            'has_schema' => $jsonLdTypes !== [],
            'has_tel_link' => ($xpath->query("//a[starts-with(@href,'tel:')]")?->length ?? 0) > 0,
            'has_mailto_link' => ($xpath->query("//a[starts-with(@href,'mailto:')]")?->length ?? 0) > 0,
            'has_contact_link' => ($xpath->query("//a[contains(translate(@href,'CONTACT','contact'),'contact')]")?->length ?? 0) > 0,
            'phone_detected' => (bool) preg_match('/\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}/', $bodyText),
        ];
    }

    /**
     * Best-effort robots.txt and sitemap.xml checks against the site root.
     *
     * @return array<string, mixed>
     */
    private function secondarySignals(string $finalUrl): array
    {
        $parts = parse_url($finalUrl);
        $root = ($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? '');

        $robots = $this->headOrGet($root.'/robots.txt');
        $robotsBody = $robots?->body() ?? '';

        return [
            'has_robots_txt' => $robots?->successful() ?? false,
            'robots_references_sitemap' => Str::contains(strtolower($robotsBody), 'sitemap:'),
            'has_sitemap_xml' => $this->headOrGet($root.'/sitemap.xml')?->successful() ?? false,
        ];
    }

    private function headOrGet(string $url): ?Response
    {
        if (! PublicHttpUrl::isSafe($url)) {
            return null;
        }

        try {
            return Http::withHeaders(['User-Agent' => config('seo-report.fetch.user_agent')])
                ->timeout((int) config('seo-report.fetch.connect_timeout'))
                ->get($url);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return list<string>
     */
    private function collectSchemaTypes(mixed $data): array
    {
        $types = [];

        if (! is_array($data)) {
            return $types;
        }

        if (isset($data['@type'])) {
            $types = array_merge($types, (array) $data['@type']);
        }

        foreach ($data as $value) {
            if (is_array($value)) {
                $types = array_merge($types, $this->collectSchemaTypes($value));
            }
        }

        return array_map('strval', $types);
    }

    private function firstString(DOMXPath $xpath, string $expression): ?string
    {
        $nodes = $xpath->query($expression);

        if (! $nodes || $nodes->length === 0) {
            return null;
        }

        $value = trim(preg_replace('/\s+/', ' ', $nodes->item(0)->textContent) ?? '');

        return $value === '' ? null : $value;
    }

    /**
     * @return array<string, string>
     */
    private function metaGroup(DOMXPath $xpath, string $expression, string $keyAttribute): array
    {
        $group = [];

        foreach ($xpath->query($expression) ?: [] as $node) {
            $key = $node->getAttribute($keyAttribute);
            $content = $node->getAttribute('content');
            if (filled($key)) {
                $group[$key] = $content;
            }
        }

        return $group;
    }

    private function visibleText(DOMDocument $document, DOMXPath $xpath): string
    {
        foreach ($xpath->query('//script | //style | //noscript') ?: [] as $node) {
            $node->parentNode?->removeChild($node);
        }

        $body = $document->getElementsByTagName('body')->item(0);

        return trim(preg_replace('/\s+/', ' ', $body?->textContent ?? '') ?? '');
    }

    /**
     * Serialize the scanned signals into a compact, model-friendly prompt input.
     *
     * @param  array<string, mixed>  $signals
     */
    public function toPromptInput(array $signals, string $industry): string
    {
        $payload = [
            'industry' => $industry,
            'signals' => $signals,
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '';
    }
}
