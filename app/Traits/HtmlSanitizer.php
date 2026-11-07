<?php

namespace App\Traits;

trait HtmlSanitizer
{
    /**
     * Sanitize HTML content to prevent Stored XSS and malicious script execution.
     */
    protected function sanitizeHtml(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        // 1. Remove dangerous script, style, and executable container tags along with their inner content
        $html = preg_replace('#<(script|style|iframe|object|embed|applet|meta|form|input|button)\b[^>]*>.*?</\1>#is', '', $html) ?? $html;
        $html = preg_replace('#<(script|style|iframe|object|embed|applet|meta|form|input|button)\b[^>]*>#is', '', $html) ?? $html;

        // 2. Remove all inline event handlers (e.g. onload, onerror, onclick, onmouseover)
        // Handles variations with whitespace, tabs, newlines, or forward slashes (e.g. <img/src=x/onerror=...>)
        $html = preg_replace('#(?:\s|/|^)on[a-z0-9_-]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)#is', '', $html) ?? $html;

        // 3. Remove javascript:, vbscript:, and non-image data: URIs in attributes
        $html = preg_replace('#(href|src|action|formaction|data)\s*=\s*(["\'])\s*(?:javascript|vbscript|data(?!\s*:\s*image\/(?:png|jpeg|jpg|webp|gif))):.*?\2#is', '', $html) ?? $html;
        $html = preg_replace('#(href|src|action|formaction|data)\s*=\s*(?:javascript|vbscript|data(?!\s*:\s*image\/(?:png|jpeg|jpg|webp|gif)))[^\s>]*#is', '', $html) ?? $html;

        return trim($html);
    }
}

