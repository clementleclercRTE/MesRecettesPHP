<?php
$mode = isset($_COOKIE['mode']) ? $_COOKIE['mode'] : 'light';
$newMode = $mode === 'light' ? 'dark' : 'light';
setcookie('mode', $newMode, time() + (86400 * 30), "/"); // 30 jours
header('Location: ' . $_SERVER['HTTP_REFERER']);
