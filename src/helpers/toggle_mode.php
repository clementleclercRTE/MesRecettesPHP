<?php
$newMode = $_GET['mode'] ?? 'light';
setcookie('mode', $newMode, time() + (86400 * 30), "/");
exit();