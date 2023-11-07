<script setup>

</script>

<template>
    <div class="alert d-flex align-items-center" :class="`alert-${variant}`" role="alert">
        <div class="flex-shrink-0">
            <i class="fa fa-fw" :class="icon"></i>
        </div>
        <div class="flex-grow-1 ms-3">
            <p class="mb-0">
                {{ message }}
            </p>
        </div>
    </div>
</template>