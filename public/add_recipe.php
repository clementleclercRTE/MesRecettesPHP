<?php
require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/ScrapService.php';

$lang = $_COOKIE['lang'] ?? 'fr';
$mode = $_COOKIE['mode'] ?? 'light';

$recipeId = $_GET['id'] ?? null;
$recipe = null;
$isEditing = false;

if ($recipeId) {
    $recipe = getRecipeById($recipeId);
    $isEditing = true;
}

// Logique de scraping
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['scrape'])) {
    $url = $_GET['url'] ?? '';
    $scrapService = new ScrapService();
    try {
        $scrapedRecipe = $scrapService->detectAndScrap($url);
        header('Content-Type: application/json');
        echo json_encode($scrapedRecipe);
        exit;
    } catch (Exception $e) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Logique de soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $url = $_POST['url'] ?? '';
    $image = $_POST['image'] ?? '';
    $isFavorite = isset($_POST['isFavorite']);

    $ingredients = [];
    $ingredientNames = $_POST['ingredient_name'] ?? [];
    $ingredientQuantities = $_POST['ingredient_quantity'] ?? [];

    for ($i = 0; $i < count($ingredientNames); $i++) {
        if (!empty($ingredientNames[$i])) {
            $ingredients[] = [
                'name' => $ingredientNames[$i],
                'quantity' => $ingredientQuantities[$i],
            ];
        }
    }

    if (!empty($name) && !empty($ingredients)) {
        if ($isEditing) {
            updateRecipe($recipeId, $name, $ingredients, $description, $url, $image, $isFavorite);
        } else {
            echo $ingredients;
            //addRecipe($name, $ingredients, $description, $url, $image, $isFavorite);
        }
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEditing ? translate('editRecipeTitle') : translate('addRecipeTitle') ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/add_recipe.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="<?= $mode ?>">
<?php include 'navbar.php'; ?>

<div class="form-container">
    <h1><?= $isEditing ? translate('editRecipeTitle') : translate('addRecipeTitle') ?></h1>
    <form method="POST" class="recipe-form">
        <div class="form-group">
            <label for="name"><?= translate('nameForm') ?></label>
            <input type="text" id="name" name="name" required value="<?= $isEditing ? htmlspecialchars($recipe['name']) : '' ?>" class="form-input">
        </div>

        <div class="form-group">
            <label><?= translate('ing') ?></label>
            <div id="ingredients-container">
                <?php if ($isEditing): ?>
                    <?php foreach ($recipe['ingredients'] as $index => $ingredient): ?>
                        <div class="ingredient-row">
                            <input type="text" name="ingredient_name[]" value="<?= htmlspecialchars($ingredient['name']) ?>" placeholder="<?= translate('ingName') ?>" required class="form-input">
                            <input type="text" name="ingredient_quantity[]" value="<?= htmlspecialchars($ingredient['quantity']) ?>" placeholder="<?= translate('ingQuantity') ?>" class="form-input">
                            <button type="button" class="remove-ingredient"><?= translate('removeIngForm') ?></button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="ingredient-row">
                        <input type="text" name="ingredient_name[]" value="" placeholder="<?= translate('ingNameForm') ?>" required class="form-input">
                        <input type="text" name="ingredient_quantity[]" value="" placeholder="<?= translate('ingQuantityForm') ?>" class="form-input">
                        <button type="button" class="remove-ingredient"><?= translate('removeIngForm') ?></button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-ingredient"><?= translate('addIngForm') ?></button>
        </div>

        <div class="form-group">
            <label for="description"><?= translate('desc') ?></label>
            <textarea id="description" name="description" class="form-textarea"><?= $isEditing ? htmlspecialchars($recipe['description']) : '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="url"><?= translate('urlForm') ?></label>
            <input type="text" id="url" name="url" value="<?= $isEditing ? htmlspecialchars($recipe['url']) : '' ?>" class="form-input">
            <button type="button" id="scrape-button"><?= translate('scrapeButton') ?></button>
        </div>

        <div class="form-group">
            <label for="image"><?= translate('imgUrlForm') ?></label>
            <input type="text" id="image" name="image" value="<?= $isEditing ? htmlspecialchars($recipe['image']) : '' ?>" class="form-input">
        </div>

        <div class="form-group checkbox">
            <label>
                <input type="checkbox" name="isFavorite" <?= $isEditing && $recipe['isFavorite'] ? 'checked' : '' ?> class="form-checkbox">
                <?= translate('favForm') ?>
            </label>
        </div>

        <button type="submit" class="submit-btn">
            <?= $isEditing ? translate('updateRecipeForm') : translate('addRecipeForm') ?>
        </button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $("#add-ingredient").click(function() {
            var newRow = $("<div class='ingredient-row'>" +
                "<input type='text' name='ingredient_name[]' placeholder='<?= translate('ingNameForm') ?>' required class='form-input'>" +
                "<input type='text' name='ingredient_quantity[]' placeholder='<?= translate('ingQuantityForm') ?>' class='form-input'>" +
                "<button type='button' class='remove-ingredient'><?= translate('removeIngForm') ?></button>" +
                "</div>");
            $("#ingredients-container").append(newRow);
        });

        $(document).on("click", ".remove-ingredient", function() {
            $(this).parent().remove();
        });


        $("#scrape-button").click(function() {
            var url = $("#url").val();
            if (url) {
                $.ajax({
                    url: '?scrape=1',
                    method: 'GET',
                    data: { url: url },
                    dataType: 'json',
                    success: function(data) {
                        $("#name").val(data.name);
                        $("#description").val(data.description);
                        $("#image").val(data.image);
                        $("input[name='isFavorite']").prop('checked', data.isFavorite);

                        // Vider le conteneur d'ingrédients existant
                        $("#ingredients-container").empty();

                        // Ajouter les nouveaux ingrédients
                        data.ingredients.forEach(function(ingredient) {
                            var newRow = $("<div class='ingredient-row'>" +
                                "<input type='text' name='ingredient_name[]' value='" + ingredient.name + "' required class='form-input'>" +
                                "<input type='text' name='ingredient_quantity[]' value='" + ingredient.quantity + "' class='form-input'>" +
                                "<button type='button' class='remove-ingredient'><?= translate('removeIngForm') ?></button>" +
                                "</div>");
                            $("#ingredients-container").append(newRow);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        var errorMessage = "Erreur lors du scraping";
                        if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                            errorMessage += ": " + jqXHR.responseJSON.error;
                        }
                        alert(errorMessage);
                    }
                });
            } else {
                alert("Veuillez entrer une URL valide.");
            }
        });
    });
</script>

</body>
</html>