<?php

function translate($key) {
    global $lang;
    $translations = require __DIR__ . '/translations.php';
    return $translations[$lang][$key] ?? $key;
}
