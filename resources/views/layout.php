<?php

// ...

?>

<!doctype html>
<html lang="es" class="scroll-smooth">

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
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->

    <!-- <script src="./index.js"></script> -->
    <link rel="stylesheet" href="./index.css" />
    <link rel="stylesheet" href="./tailwindcss.min.css" />
</head>

<body class="light-mode">
    <?php Flight::render('components/navbar') ?>

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <?= $content ?>
        </div>
    </main>

    <?php Flight::render('components/footer') ?>
</body>

</html>
