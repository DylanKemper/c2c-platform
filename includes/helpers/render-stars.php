<?php

function renderStars(float $rating): string
{
    $rating = max(0, min(5, $rating));

    $full = floor($rating);
    $half = ($rating - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;

    $html = '';

    for ($i = 0; $i < $full; $i++) {
        $html .= '<i class="bi bi-star-fill star"></i>';
    }

    if ($half) {
        $html .= '<i class="bi bi-star-half star"></i>';
    }

    for ($i = 0; $i < $empty; $i++) {
        $html .= '<i class="bi bi-star star-empty"></i>';
    }

    return $html;
}
