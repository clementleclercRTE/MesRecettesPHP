<?php
require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../src/helpers.php';

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
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/recipe_card.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="<?= $mode ?>">

<?php include 'navbar.php'; ?>

<h1><?= translate('title') ?></h1>

<?php if (empty($recipes)): ?>
    <p class="no-recipes"><?= translate('noRecipe') ?></p>
<?php else: ?>
    <div class="recipe-grid">
        <?php foreach ($recipes as $recipe): ?>
            <div class="recipe-card">
                <div class="recipe-image" style="background-image: url('<?= htmlspecialchars($recipe['image'] ?: 'img/default-recipe.jpg') ?>');">
                    <?php if ($recipe['isFavorite']): ?>
                        <span class="favorite-badge"><i class="fas fa-star"></i></span>
                    <?php endif; ?>
                </div>
                <div class="recipe-content">
                    <h2><?= htmlspecialchars($recipe['name']) ?></h2>
                    <p class="recipe-description"><?= htmlspecialchars(substr($recipe['description'], 0, 100)) ?>...</p>
                    <a href="recipe_details.php?id=<?= $recipe['id'] ?>" class="view-recipe"><?= translate('viewRecipe') ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</body>
</html>