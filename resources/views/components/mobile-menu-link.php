<?php

// ...

?>

<a
    href="<?= $href ?? 'javascript:' ?>"
    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-violet-600 hover:bg-violet-50">
    <?= $slot ?? '' ?>
</a>
