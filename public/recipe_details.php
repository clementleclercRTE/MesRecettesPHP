<?php
require_once __DIR__ . '/../src/database/database.php';
require_once __DIR__ . '/../src/helpers/helpers.php';

$lang = $_COOKIE['lang'] ?? 'fr';
$mode = $_COOKIE['mode'] ?? 'light';

$recipeId = $_GET['id'] ?? null;

if (!$recipeId) {
    header('Location: index.php');
    exit;
}

$recipe = getRecipeById($recipeId);

if (!$recipe) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    deleteRecipe($recipeId);
    header('Location: index.php');
}


?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recipe['name']) ?> - <?= translate('RecipeDetails') ?></title>
    <link rel="stylesheet" href="../template/components/css/navbar.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/recipe_details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="<?= $mode ?>">
<?php include '../template/components/navbar.php'; ?>

<div class="recipe-container">

   <div class="title-container">
       <h1 class="recipe-title"><?= htmlspecialchars($recipe['name']) ?></h1>
       <?php if ($recipe['url']): ?>
           <a href="<?= htmlspecialchars($recipe['url']) ?>" target="_blank" class="original-recipe-link">
               <i class="fas fa-external-link-alt"></i>
           </a>
       <?php endif; ?>
   </div>


    <?php if ($recipe['image']): ?>
        <div class="recipe-image" style="background-image: url('<?= htmlspecialchars($recipe['image']) ?>');">
            <?php if ($recipe['isFavorite']): ?>
                <span class="favorite-badge"><i class="fas fa-star"></i></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="recipe-content">
        <section class="recipe-section">
            <h2><?= translate('Ingredients') ?></h2>
            <ul class="ingredients-list">
                <?php foreach ($recipe['ingredients'] as $ingredient): ?>
                    <li>
                        <?= htmlspecialchars($ingredient['quantity']) ?>
                        <?= htmlspecialchars($ingredient['name']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <div class="recipe-content">
            <section class="recipe-steps">
                <h2><?= translate('recipeStep') ?></h2>
                <ul class="steps-list">
                    <?php foreach ($recipe['steps'] as $step): ?>
                        <li class="step-item">
                            <div class="step-number"><?= htmlspecialchars($step['num']) ?></div>
                            <div class="step-description"><?= htmlspecialchars($step['description']) ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>


        <div class="edit-form">

            <button onclick="window.location.href='add_recipe.php?id=<?= $recipe['id'] ?>'" class="edit-recipe-button">
                <i class="fas fa-edit"></i>
                <span><?= translate('editRecipe') ?></span>
            </button>

            <form method="POST"  onsubmit="return confirm('<?= translate('confirmDeleteRecipe') ?>');">
                <input type="hidden" name="id" value="<?= $recipe['id'] ?>">
                <button type="submit" class="delete-recipe-button">
                    <i class="fas fa-trash"></i>
                    <span><?= translate('trashRecipe') ?></span>
                </button>
            </form>
        </div>
    </div>

    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> <?= translate('backToList') ?>
    </a>

</div>
</body>
</html>