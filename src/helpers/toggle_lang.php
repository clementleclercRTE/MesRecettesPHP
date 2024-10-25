<?php
//Permet de changer la variable 'lang' et de la garder stockée dans les cookies
//recharge la page pour mettre à jour les elements

$lang = $_COOKIE['lang'] ?? 'fr';
$newLang = $lang === 'fr' ? 'en' : 'fr';
setcookie('lang', $newLang, time() + (86400 * 30), "/"); // 30 jours
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();