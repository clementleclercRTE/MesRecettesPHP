# Gestionnaire de Recettes - Guide d'Installation

## Prérequis

- PHP 
- SQLite3
- Composer (pour les dépendances)

## Installation

1. **Installer les dépendances**
```bash
sudo apt update
sudo apt install composer
composer install 
```
Cette commande installera DiDom, nécessaire pour la fonctionnalité de scraping des recettes.

3. **Configuration de la base de données**
- La base de données SQLite sera automatiquement créée au premier lancement
- Le fichier de base de données sera créé dans : `database/recetteDb.db`
- Les tables seront créées automatiquement grâce à la fonction `createTables()` dans `database.php`

4. **Lancer le serveur de développement**
```bash
php -S localhost:8000 -t 
```

## Fonctionnalités Disponibles

Une fois le serveur lancé, vous pouvez accéder à :
- Liste des recettes : `http://localhost:8000/public/index`
- Ajouter une recette : `http://localhost:8000/add_recipe.php`
- Voir une recette : `http://localhost:8000/recipe_details.php?id=[ID]`

## Notes Importantes

- Le scraping de recettes fonctionne uniquement avec les URLs de Marmiton
- Les préférences (langue, thème) sont stockées dans les cookies

