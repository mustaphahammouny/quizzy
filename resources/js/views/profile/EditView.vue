<script setup>
import UpdateProfile from "@/views/profile/components/UpdateProfile.vue";
import UpdatePassword from "@/views/profile/components/UpdatePassword.vue";
</script>

<template>
    <BasePageHeading title="Profile" />

    <div class="content">
        <UpdateProfile />
    
        <UpdatePassword />
    </div>
</template>
