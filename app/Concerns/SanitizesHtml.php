<?php

namespace App\Concerns;

trait SanitizesHtml
{
    /**
     * Sanitize TipTap-generated HTML against an allowlist of tags and attributes.
     *
     * Admins are trusted authors, but we still scrub event handlers and script-y
     * attributes so a copy-pasted snippet from elsewhere can't introduce XSS.
     */
    protected function sanitizePostHtml(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        $allowedTags = [
            'p', 'br', 'strong', 'em', 'u', 's', 'code', 'pre', 'blockquote',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol', 'li',
            'a', 'img', 'figure', 'figcaption',
            'hr', 'span',
        ];

        $allowedAttrs = [
            'a' => ['href', 'title', 'target', 'rel'],
            'img' => ['src', 'alt', 'title'],
        ];

        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="UTF-8"><div>'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        /** @var iterable<\DOMElement> $nodes */
        $nodes = iterator_to_array($xpath->query('//*'));

        foreach ($nodes as $node) {
            $tag = strtolower($node->tagName);

            if (! in_array($tag, $allowedTags, true)) {
                while ($node->firstChild) {
                    $node->parentNode?->insertBefore($node->firstChild, $node);
                }
                $node->parentNode?->removeChild($node);

                continue;
            }

            $allowed = $allowedAttrs[$tag] ?? [];
            foreach (iterator_to_array($node->attributes) as $attr) {
                if (! in_array(strtolower($attr->nodeName), $allowed, true)) {
                    $node->removeAttribute($attr->nodeName);
                }
            }

            if ($tag === 'a') {
                $href = $node->getAttribute('href');
                if ($href !== '' && ! preg_match('#^(https?:|mailto:|/|\#)#i', $href)) {
                    $node->removeAttribute('href');
                }
                if ($node->getAttribute('target') === '_blank' && $node->getAttribute('rel') === '') {
                    $node->setAttribute('rel', 'noopener noreferrer');
                }
            }

            if ($tag === 'img') {
                $src = $node->getAttribute('src');
                if (! preg_match('#^(https?:|/)#i', $src)) {
                    $node->parentNode?->removeChild($node);
                }
            }
        }

        $wrapper = $dom->getElementsByTagName('div')->item(0);
        $output = '';
        if ($wrapper) {
            foreach ($wrapper->childNodes as $child) {
                $output .= $dom->saveHTML($child);
            }
        }

        return trim($output);
    }
}
