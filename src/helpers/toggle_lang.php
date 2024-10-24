<?php
$lang = $_COOKIE['lang'] ?? 'fr';
$newLang = $lang === 'fr' ? 'en' : 'fr';
setcookie('lang', $newLang, time() + (86400 * 30), "/"); // 30 jours
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();