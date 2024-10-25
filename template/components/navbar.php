<?php
$lang = $_COOKIE['lang'] ?? 'fr';
$mode = $_COOKIE['mode'] ?? 'light';
require_once __DIR__ . '/../../src/helpers/helpers.php';

// Détermine si le lien actuel est actif
function isActiveLink($path) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return $currentPage === $path ? 'active' : '';
}
?>
<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo/Titre -->
        <div class="navbar-brand">
            <a href="../../public/index.php">
                <i class="fas fa-utensils"></i>
                <span><?= translate('appName') ?></span>
            </a>
        </div>

        <!-- Liens de navigation -->
        <div class="navbar-links">
            <a href="../../public/index.php" class="<?= isActiveLink('index.php') ?>">
                <i class="fas fa-home"></i>
                <span><?= translate('home') ?></span>
            </a>

            <a href="../../public/add_recipe.php" class="<?= isActiveLink('add_recipe.php') ?>">
                <i class="fas fa-plus-circle"></i>
                <span><?= translate('add') ?></span>
            </a>
        </div>

        <!-- Actions (droite) -->
        <div class="navbar-actions">
            <button class="theme-toggle" onclick="toggleMode()" aria-label="Toggle theme">
                <?php if ($mode === 'dark'): ?>
                    <i class="fas fa-sun"></i>
                <?php else: ?>
                    <i class="fas fa-moon"></i>
                <?php endif; ?>
            </button>

            <button class="lang-toggle" onclick="window.location.href='../../src/helpers/toggle_lang.php'" aria-label="Change language">
                <?php if ($lang === 'fr'): ?>
                    <span>FR</span>
                <?php else: ?>
                    <span>EN</span>
                <?php endif; ?>
            </button>
        </div>

        <!-- Menu burger pour mobile -->
        <button class="navbar-burger" onclick="toggleMobileMenu()" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>

<script>

    function toggleMobileMenu() {
        document.querySelector('.navbar-links').classList.toggle('active');
        document.querySelector('.navbar-actions').classList.toggle('active');
        document.querySelector('.navbar-burger').classList.toggle('active');
    }

    async function toggleMode() {
        const body = document.body;
        const currentMode = body.className === 'dark' ? 'light' : 'dark';
        body.className = currentMode;

        //appelle la fonction php pour sauvergarder les cookies
        try {
            await fetch('../../src/helpers/toggle_mode.php?mode=' + currentMode);
        } catch (error) {
            console.error('Erreur:', error);
        }
    }

</script>