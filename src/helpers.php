<?php

function translate($key, $lang = null) {
    global $lang;
    $translations = require __DIR__ . '/translations.php';
    return $translations[$lang][$key] ?? $key;
}
