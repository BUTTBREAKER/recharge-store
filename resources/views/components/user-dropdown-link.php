<?php

// ...

?>

<a
    href="<?= $href ?? 'javascript:' ?>"
    class="block px-4 py-2 text-sm text-gray-700 hover:bg-violet-50">
    <?= $slot ?? '' ?>
</a>
