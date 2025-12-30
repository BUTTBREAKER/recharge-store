<?php

use Leaf\Http\Session;
use RECHARGE\Enums\SessionKey;

?>

<!doctype html>
<html
    lang="es"
    class="scroll-smooth"
    data-bs-theme="<?= Session::get(SessionKey::UI_THEME->name, '') ?>"
    :data-bs-theme="tema"
    x-data='{
        tema: document.documentElement.dataset.bsTheme || (matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"),

        get temaInverso() {
          return this.tema === "dark" ? "light" : "dark";
        },
    }'
    x-init='
        matchMedia("(prefers-color-scheme: dark)").addEventListener(
          "change",
          (evento) => {
            tema = evento.matches ? "dark" : "light";
          },
        );

        $watch("tema", (nuevoTema) => {
          fetch("./ajax/ajustes/tema", {
            method: "post",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              tema: nuevoTema,
            }),
          });
        });
    '>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title><?= $title ?? 'SisifoStore - Recargas MLBB' ?></title>
    <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />

    <!-- Google Fonts: Inter -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!--
        Tailwind CSS (CDN - Development Mode)
        Note: "cdn.tailwindcss.com should not be used in production" warning is expected.
        We use it for rapid MVP development.
    -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="./index.js"></script>
    <link rel="stylesheet" href="./index.css" />
</head>

<body
    class="transition-colors duration-300"
    :class="`${tema}-mode`">
    <?php Flight::render('components/navbar') ?>

    <main class="pt-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?= $content ?>
    </main>

    <?php Flight::render('components/footer') ?>

    <script>
        // Mobile menu toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
