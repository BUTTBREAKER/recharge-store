<?php

use Leaf\Http\Session;
use App\Enums\SessionKey;

?>

<!doctype html>
<html
    lang="es"
    class="scroll-smooth"
    data-theme="<?= Session::get(SessionKey::UI_THEME->name, '') ?>"
    :data-theme="theme"
    x-data='{
        theme: (
            document.documentElement.dataset.theme
            || (matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light")
        ),

        mobileMenuOpen: false,
    }'
    x-init='
        matchMedia("(prefers-color-scheme: dark)").addEventListener(
            "change",
            (event) => {
                theme = event.matches ? "dark" : "light";
            },
        );

        $watch("theme", (newTheme) => {
            fetch("./ajax/settings/theme", {
                method: "post",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    theme: newTheme,
                }),
            });
        });
    '>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title><?= $title ?? 'SisifoStore - Recargas MLBB' ?></title>
    <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
    <link rel="icon" href="./images/favicon.svg" />

    <!-- Google Fonts: Inter -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="./index.css" />
</head>

<body
    class="transition-colors duration-300 dark:text-[#e0d5f0]"
    :class="`${theme}-mode`">
    <?php Flight::render('components/navbar') ?>

    <main class="pt-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?= $content ?>
    </main>

    <?php Flight::render('components/footer') ?>
</body>

</html>
