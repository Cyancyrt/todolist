<?php

if (!function_exists('truncate_text')) {
    function truncate_text(string $text, int $limit = 100): string
    {
        $cleanText = strip_tags($text); // buang tag HTML
        if (strlen($cleanText) <= $limit) {
            return esc($cleanText);
        }
        return esc(substr($cleanText, 0, $limit)) . '...';
    }
}
