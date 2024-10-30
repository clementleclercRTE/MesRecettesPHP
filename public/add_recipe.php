<?php
require_once __DIR__ . '/../src/database/database.php';
require_once __DIR__ . '/../src/helpers/helpers.php';

$lang = $_COOKIE['lang'] ?? 'fr';
$mode = $_COOKIE['mode'] ?? 'light';

$recipeId = $_GET['id'] ?? null;
$recipe = null;
$isEditing = false;

$stepsCount = 0;
$ingredientsCount = 0;

if ($recipeId) {
    $recipe = getRecipeById($recipeId);
    $isEditing = true;
    $stepsCount = count($recipe['steps']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $url = $_POST['url'] ?? '';
    $image = $_POST['image'] ?? '';
    $isFavorite = isset($_POST['isFavorite']);

    $ingredients = [];
    $ingredientNames = $_POST['ingredient_name'] ?? [];
    $ingredientQuantities = $_POST['ingredient_quantity'] ?? [];

    $steps = [];
    $stepsDesc = $_POST['step_desc'] ?? [];
    $stepsNum = $_POST['step_num'] ?? [];

    for ($i = 0; $i < count($ingredientNames); $i++) {
        if (!empty($ingredientNames[$i])) {
            $ingredients[] = [
                'name' => $ingredientNames[$i],
                'quantity' => $ingredientQuantities[$i],
            ];
        }
    }

    for ($i = 0; $i < count($stepsNum); $i++) {
        if (!empty($stepsDesc[$i])) {
            $steps[] = [
                'description' => $stepsDesc[$i],
                'num' => $stepsNum[$i],
            ];
        }
    }

    if (!empty($name) && !empty($ingredients)) {
        if ($isEditing) {
            updateRecipe($recipeId, $name, $ingredients, $description, $url, $image, $isFavorite, $steps);
        } else {
             $recipeId = addRecipe($name, $ingredients, $description, $url, $image, $isFavorite, $steps);
        }
        header('Location: recipe_details.php?id=' . $recipeId);
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
    <link rel="stylesheet" href="../template/components/css/navbar.css">
    <link rel="stylesheet" href="css/add_recipe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="<?= $mode ?>">

<?php include '../template/components/navbar.php'; ?>

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
                            <button type="button" class="remove-ingredient">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="ingredient-row">
                        <input type="text" name="ingredient_name[]" value="" placeholder="<?= translate('ingNameForm') ?>" required class="form-input">
                        <input type="text" name="ingredient_quantity[]" value="" placeholder="<?= translate('ingQuantityForm') ?>" class="form-input">
                        <button type="button" class="remove-ingredient">
                            <i class="fas fa-trash"></i>
                        </button>                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-ingredient"><?= translate('addIngForm') ?></button>
        </div>

        <div class="form-group">
            <label><?= translate('stepDesc') ?></label>
            <div id="steps-container">
                <?php if ($isEditing): ?>
                    <?php foreach ($recipe['steps'] as $index => $step): ?>
                        <div class="step-row">
                            <input type="text" name="step_desc[]" value="<?= htmlspecialchars($step['description']) ?>" placeholder="<?= translate('stepDesc') ?>" required class="form-input">
                            <input type="text" name="step_num[]" value="<?= htmlspecialchars($step['num']) ?>" placeholder="<?= translate('stepNum') ?>" class="form-input">
                            <button type="button" class="remove-step">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="step-row">
                        <input type="text" name="step_desc[]" value="" placeholder="<?= translate('stepDesc') ?>" required class="form-input">
                        <input type="text" name="step_num[]" value=1 placeholder="<?= translate('stepNum')  ?>" class="form-input">
                        <button type="button" class="remove-ingredient">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-step"><?= translate('addStepForm') ?></button>
        </div>

        <div class="form-group">
            <label for="description"><?= translate('desc') ?></label>
            <textarea id="description" name="description" class="form-textarea"><?= $isEditing ? htmlspecialchars($recipe['description']) : '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="url"><?= translate('urlForm') ?></label>
            <input type="text" id="url" name="url" value="<?= $isEditing ? htmlspecialchars($recipe['url']) : '' ?>" class="form-input">
            <button type="button" id="scrape-recipe-btn" class="scrape-recipe-btn">
                <i class="fas fa-trash"></i>
            </button>
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

        function updateStepNumbers() {
            $('.step-row').each(function(index) {
                $(this).find('input[name="step_num[]"]').val(index + 1);
            });
        }

        $("#add-ingredient").click(function() {
            var newRow = $("<div class='ingredient-row'>" +
                "<input type='text' name='ingredient_name[]' placeholder='<?= translate('ingNameForm') ?>' required class='form-input'>" +
                "<input type='text' name='ingredient_quantity[]' placeholder='<?= translate('ingQuantityForm') ?>' class='form-input'>" +
                "<button type='button' class='remove-ingredient'> <i class='fas fa-trash'></i></button>"+
                "</div>");
            $("#ingredients-container").append(newRow);
        });

        $(document).on("click", ".remove-ingredient", function() {
            $(this).parent().remove();
        });


        $("#add-step").click(function() {
            var newRow = $("<div class='step-row'>" +
                "<input type='text' name='step_desc[]' placeholder='<?= translate('stepDesc') ?>' required class='form-input'>" +
                "<input type='text' name='step_num[]' placeholder='><?= translate('stepNum') ?>'  value='<?= $stepsCount ?>' class='form-input'>" +
                "<button type='button' class='remove-step'> <i class='fas fa-trash'></i></button>"+
                "</div>");
            $("#steps-container").append(newRow);
            updateStepNumbers();
        });

        $(document).on("click", ".remove-step", function() {
            $(this).parent().remove();
            updateStepNumbers();
        });

        $("#scrape-recipe-btn").click(async function() {
            const button = $(this);
            const url = $("#url").val();
            if (!url) return;

            try {
                button.addClass('loading');
                button.find('i').removeClass('fa-download').addClass('fa-spinner');

                const recipe = await recipeScraper.scrapeRecipe(url);
                $("#name").val(recipe.name);
                $("#image").val(recipe.image);

                $("#ingredients-container").empty();
                recipe.ingredients.forEach(ing => {
                    const newRow = $("<div class='ingredient-row'>" +
                        "<input type='text' name='ingredient_name[]' value='" + ing.name + "' required class='form-input'>" +
                        "<input type='text' name='ingredient_quantity[]' value='" + ing.quantity + "' class='form-input'>" +
                        "<button type='button' class='remove-ingredient'><i class='fas fa-trash'></i></button>" +
                        "</div>");
                    $("#ingredients-container").append(newRow);
                });
            } catch (error) {
                alert("Erreur lors du scraping : " + error.message);
            } finally {
                button.removeClass('loading');
                button.find('i').removeClass('fa-spinner').addClass('fa-download');
            }
        });




    });
</script>

</body>
</html>