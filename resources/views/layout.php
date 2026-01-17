<?php

use Leaf\Http\Session;
use App\Enums\SessionKey;

?>

<!doctype html>
<html
    lang="es"
    class="scroll-smooth"
    x-data='{
        isDark: localStorage.getItem("theme") === "dark" || (!localStorage.getItem("theme") && window.matchMedia("(prefers-color-scheme: dark)").matches),
        mobileMenuOpen: false,
        init() {
            this.updateTheme();
        },
        updateTheme() {
            if (this.isDark) {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
        },
        toggleTheme() {
            this.isDark = !this.isDark;
            localStorage.setItem("theme", this.isDark ? "dark" : "light");
            this.updateTheme();
            // Sync with server
            fetch("./ajax/settings/theme", {
                method: "post",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ theme: this.isDark ? "dark" : "light" }),
            });
        }
    }'
    x-init="init()"
    :class="{ 'dark': isDark }">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title><?= $title ?? 'FearSold - Recargas MLBB' ?></title>
    <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
    <link rel="icon" href="./images/favicon.svg" />

    <!-- Google Fonts: Inter -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="./index.css" />
</head>

<body class="transition-colors duration-300 min-h-screen">
    <?php Flight::render('components/navbar') ?>

    <main class="pt-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?= $content ?>
    </main>

    <?php Flight::render('components/footer') ?>
</body>

</html>

