<?php
$lang = $_COOKIE['lang'] ?? 'fr';
$mode = $_COOKIE['mode'] ?? 'light';
require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../src/helpers.php';


?>
<nav>
    <ul>
        <li><a href="index.php"><?= translate('home') ?></a></li>
        <li><a href="add_recipe.php"><?= translate('add') ?></a></li>
        <li><a href="toggle_mode.php"><?= translate('mode') ?></a></li>
        <li><a href="toggle_lang.php"><?= translate('lang') ?></a></li>
    </ul>
</nav>