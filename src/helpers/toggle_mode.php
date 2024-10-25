<?php
//permet de changer la variale mode et de la stocker dans les cookies
//ne recharge pas la page, le js dans "navbar.php" permet de changer directement le theme

$newMode = $_GET['mode'] ?? 'light';
setcookie('mode', $newMode, time() + (86400 * 30), "/");
exit();