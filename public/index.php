<?php
require_once __DIR__ . '/../src/database/database.php';
require_once __DIR__ . '/../src/helpers/helpers.php';
require_once __DIR__ . '/../template/components/recipe-card.php';

$lang = $_COOKIE['lang'] ?? 'fr';
$mode = $_COOKIE['mode'] ?? 'light';

$recipes = getAllRecipes();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translate('Titre') ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../template/components/css/navbar.css">
    <link rel="stylesheet" href="../template/components/css/recipe_card.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="<?= $mode ?>">

<?php include '../template/components/navbar.php'; ?>

<h1><?= translate('recipesList') ?></h1>

<?php
// Affichage de la grille de recettes avec options personnalisées
renderRecipeGrid($recipes);
?>
</body>
</html>