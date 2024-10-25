# 🍳 MesRecettes - Application de Gestion de Recettes

Une application web permettant de gérer et organiser vos recettes de cuisine préférées.

## 📋 Fonctionnalités

- ✨ Interface utilisateur intuitive et responsive
- 🌓 Mode sombre/clair
- 🌍 Support multilingue (FR/EN)
- ➕ Ajout, modification et suppression de recettes
- ⭐ Système de favoris
- 🖼️ Support des images pour les recettes
- 🔍 Recherche de recettes
- 📱 Design adaptatif (mobile-first)

## 🛠️ Technologies Utilisées

- **Frontend** :
    - HTML5
    - CSS3 (Design responsive)
    - JavaScript (Vanilla)
    - Font Awesome (Icons)

- **Backend** :
    - PHP 8.x
    - SQLite 3

## 📦 Installation

1. **Prérequis**
   ```bash
   - PHP 8.0 ou supérieur
   - SQLite3
   - Un serveur web (Apache/Nginx)
   ```

2. **Cloner le repository**
   ```bash
   git clone https://github.com/votre-username/MesRecettes.git
   cd MesRecettes
   ```

3. **Configuration**
    - Créer la base de données SQLite :
      ```bash
      sqlite3 database/recetteDb.db < database/schema.sql
      ```
    - Vérifier les permissions :
      ```bash
      chmod 755 public/
      chmod 644 database/recetteDb.db
      ```

## 📁 Structure du Projet

```
MesRecettes/
├── database/
│   └── recetteDb.db
├── public/
│   ├── css/
│   ├── assets/
│   └── index.php
├── src/
│   ├── database/
│   │   └── database.php
│   └── helpers/
│       ├── helpers.php
│       ├── toggle_lang.php
│       └── translations.php
└── template/
    └── components/
        └── navbar.php
```

## 🚀 Utilisation

1. Accédez à l'application via votre navigateur :
   ```
   http://localhost/MesRecettes/public/
   ```

2. Fonctionnalités principales :
    - Ajout d'une recette : Cliquez sur "+" dans la barre de navigation
    - Modification : Bouton "Éditer" sur la page de détails
    - Suppression : Bouton "Supprimer" sur la page de détails
    - Changement de thème : Icône lune/soleil
    - Changement de langue : Bouton FR/EN

## 🔄 API Routes

- `GET /` : Page d'accueil avec liste des recettes
- `GET /recipe_details.php?id={id}` : Détails d'une recette
- `GET/POST /add_recipe.php` : Ajout/Modification de recette
- `POST /delete_recipe.php` : Suppression de recette

## 💾 Base de Données

**Table Recipes**
```sql
CREATE TABLE recipes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    image TEXT,
    url TEXT,
    isFavorite BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🔐 Sécurité

- Protection contre les injections SQL (Requêtes préparées)
- Validation des entrées utilisateur
- Protection XSS (htmlspecialchars)
- Sécurisation des uploads d'images

## 🛠️ Configuration

Les paramètres de configuration se trouvent dans `src/config.php` :
- Mode debug
- Chemin de la base de données
- Langue par défaut
- Limites upload

## 🤝 Contribution

1. Forker le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commiter vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Pusher sur la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 Licence

Distribué sous la licence MIT. Voir `LICENSE` pour plus d'informations.

## 📧 Contact

Votre Nom - [@votre_twitter](https://twitter.com/votre_twitter)

Lien du projet : [https://github.com/votre-username/MesRecettes](https://github.com/votre-username/MesRecettes)

## 🙏 Remerciements

- [Font Awesome](https://fontawesome.com)
- [SQLite](https://www.sqlite.org)
- [PHP](https://www.php.net)