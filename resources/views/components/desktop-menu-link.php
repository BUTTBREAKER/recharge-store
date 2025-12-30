<?php

// ...

?>

<a
    href="<?= $href ?? 'javascript:' ?>"
    class="text-gray-600 hover:text-violet-600 font-medium transition">
    <?= $slot ?? '' ?>
</a>
