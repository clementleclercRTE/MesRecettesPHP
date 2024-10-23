<?php
function getDatabaseConnection() {
    $databasePath = __DIR__ . '/../database/recetteDb.db';
    try {
        $pdo = new PDO('sqlite:' . $databasePath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

function createTables() {
    $pdo = getDatabaseConnection();

    // Création de la table recipes
    $sql = "CREATE TABLE IF NOT EXISTS recipes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        url TEXT,
        image TEXT,
        isFavorite INTEGER DEFAULT 0
    )";
    $pdo->exec($sql);

    // Création de la table ingredients
    $sql = "CREATE TABLE IF NOT EXISTS ingredients (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        recipe_id INTEGER,
        name TEXT NOT NULL,
        quantity TEXT,
        FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
}

function addRecipe($name, $ingredients, $description, $url, $image, $isFavorite) {
    $pdo = getDatabaseConnection();
    $pdo->beginTransaction();

    try {
        // Insérer la recette
        $sql = "INSERT INTO recipes (name, description, url, image, isFavorite) 
                VALUES (:name, :description, :url, :image, :isFavorite)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':url' => $url,
            ':image' => $image,
            ':isFavorite' => $isFavorite ? 1 : 0
        ]);
        $recipeId = $pdo->lastInsertId();



        // Insérer les ingrédients
        $sql = "INSERT INTO ingredients (recipe_id, name, quantity) VALUES (:recipe_id, :name, :quantity)";
        $stmt = $pdo->prepare($sql);

        foreach ($ingredients as $ingredient) {
            $stmt->execute([
                ':recipe_id' => $recipeId,
                ':name' => $ingredient['name'],
                ':quantity' => $ingredient['quantity'] ?? ''
            ]);
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        // Log l'erreur pour le débogage
        error_log("Erreur dans addRecipe: " . $e->getMessage());
        throw $e;
    }
}

function updateRecipe($id, $name, $ingredients, $description, $url, $image, $isFavorite) {
    $pdo = getDatabaseConnection();
    $pdo->beginTransaction();

    try {
        $sql = "UPDATE recipes SET name = :name, description = :description, url = :url, 
                image = :image, isFavorite = :isFavorite WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':description' => $description,
            ':url' => $url,
            ':image' => $image,
            ':isFavorite' => $isFavorite ? 1 : 0
        ]);

        // Supprimer les anciens ingrédients
        $sql = "DELETE FROM ingredients WHERE recipe_id = :recipe_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':recipe_id' => $id]);

        // Ajouter les nouveaux ingrédients
        $sql = "INSERT INTO ingredients (recipe_id, name, quantity) VALUES (:recipe_id, :name, :quantity)";
        $stmt = $pdo->prepare($sql);

        foreach ($ingredients as $ingredient) {
            $stmt->execute([
                ':recipe_id' => $id,
                ':name' => $ingredient['name'],
                ':quantity' => $ingredient['quantity']
            ]);
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function getRecipeById($id) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recipe) {
        $stmt = $pdo->prepare("SELECT name, quantity FROM ingredients WHERE recipe_id = :recipe_id");
        $stmt->execute([':recipe_id' => $id]);
        $recipe['ingredients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $recipe;
}

function getAllRecipes() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->query("SELECT * FROM recipes");
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($recipes as &$recipe) {
        $stmt = $pdo->prepare("SELECT name, quantity FROM ingredients WHERE recipe_id = :recipe_id");
        $stmt->execute([':recipe_id' => $recipe['id']]);
        $recipe['ingredients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $recipes;
}

//creer les tables au demarrage si elles n'existent pas.
createTables();