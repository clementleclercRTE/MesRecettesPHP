<?php
/**
 * Composant de carte de recette
 * @param array $recipe Les données de la recette
 */
function renderRecipeCard($recipe) {
    $defaultImage = 'https://images.unsplash.com/photo-1466637574441-749b8f19452f?auto=format&fit=crop&w=500&q=60';
    $descriptionLength = $options['descriptionLength'] ?? 100;
    ?>
    <div class="recipe-card">
        <div class="recipe-image" style="background-image: url('<?= htmlspecialchars($recipe['image'] ?: $defaultImage) ?>');">
            <?php if ($recipe['isFavorite']): ?>
                <span class="favorite-badge"><i class="fas fa-star"></i></span>
            <?php endif; ?>
        </div>
        <div class="recipe-content">
            <h2><?= htmlspecialchars($recipe['name']) ?></h2>
            <p class="recipe-description">
                <?= htmlspecialchars(mb_substr($recipe['description'], 0, $descriptionLength)) ?>...
            </p>
            <a href="recipe_details.php?id=<?= $recipe['id'] ?>" class="view-recipe">
                <?= translate('viewRecipe') ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Affiche une grille de cartes de recettes
 * @param array $recipes Tableau de recettes
 * @param array $options Options supplémentaires (optionnel)
 */
function renderRecipeGrid($recipes, $options = []) {
    if (empty($recipes)): ?>
        <p class="no-recipes"><?= translate('noRecipe') ?></p>
    <?php else: ?>
        <div class="recipe-grid">
            <?php foreach ($recipes as $recipe):
                renderRecipeCard($recipe, $options);
            endforeach; ?>
        </div>
    <?php endif;
}
?>