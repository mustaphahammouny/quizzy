<script setup>
import Empty from "@/assets/media/photos/empty.gif";
</script>

<template>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 mb-4">
        <div class="col mx-auto text-center">
            <img class="img-fluid" :src="Empty" alt="empty" />
            <h2 class="fw-bold text-dark">No data found</h2>
        </div>
    </div>
</template>
