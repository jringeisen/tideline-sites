<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * Validates that a user-supplied URL is a public http/https address safe to
 * fetch server-side. Guards against SSRF by rejecting private/reserved hosts,
 * literal IPs, non-standard ports, and credentials in the URL.
 */
class PublicHttpUrl implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! self::isSafe($value)) {
            $fail('Please enter a valid, publicly reachable website address.');
        }
    }

    /**
     * Whether the given URL is a public http/https address safe to fetch.
     */
    public static function isSafe(string $url): bool
    {
        $parts = parse_url($url);

        if ($parts === false || ! isset($parts['scheme'], $parts['host'])) {
            return false;
        }

        if (! in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            return false;
        }

        // Reject embedded credentials (user:pass@host).
        if (isset($parts['user']) || isset($parts['pass'])) {
            return false;
        }

        // Only allow standard web ports.
        if (isset($parts['port']) && ! in_array($parts['port'], [80, 443], true)) {
            return false;
        }

        return self::hostIsPublic($parts['host']);
    }

    /**
     * Whether the host resolves only to public, routable IP addresses.
     */
    public static function hostIsPublic(string $host): bool
    {
        $host = strtolower(trim($host, '.'));

        if ($host === '' || $host === 'localhost') {
            return false;
        }

        if (str_ends_with($host, '.local') || str_ends_with($host, '.internal')) {
            return false;
        }

        // Reject literal IP hosts outright — legitimate sites use hostnames.
        if (filter_var($host, FILTER_VALIDATE_IP) !== false) {
            return false;
        }

        $ips = self::resolve($host);

        if ($ips === []) {
            return false;
        }

        foreach ($ips as $ip) {
            if (! self::ipIsPublic($ip)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether a single IP address is public (not private or reserved).
     */
    public static function ipIsPublic(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE,
        ) !== false;
    }

    /**
     * Resolve a hostname to its IPv4 and IPv6 addresses.
     *
     * @return list<string>
     */
    private static function resolve(string $host): array
    {
        $ips = [];

        $v4 = gethostbynamel($host);
        if (is_array($v4)) {
            $ips = array_merge($ips, $v4);
        }

        $v6 = @dns_get_record($host, DNS_AAAA);
        if (is_array($v6)) {
            foreach ($v6 as $record) {
                if (isset($record['ipv6'])) {
                    $ips[] = $record['ipv6'];
                }
            }
        }

        return array_values(array_unique($ips));
    }
}
