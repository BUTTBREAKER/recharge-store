<?php

// ...

?>

<button
    class="w-[60px] h-[30px] bg-[#e5e7eb] dark:bg-[#8b5cf6] relative rounded-full transition-colors duration-300 cursor-pointer"
    @click="tema = temaInverso"
    title="Cambiar tema">
    <div class="absolute top-[3px] left-[3px] w-6 h-6 bg-white rounded-full transition-transform duration-300 flex items-center justify-center dark:translate-x-[30px] dark:bg-[#1a0a2e]">
        <span>🌙</span>
    </div>
</button>
