<script setup>
import { useTemplateStore } from "@/stores/template.store";

import BaseLayout from "@/layouts/BaseLayout.vue";

// Template store
const template = useTemplateStore();

// Set default elements for this layout
template.setLayout({
    header: false,
    sidebar: false,
    sideOverlay: false,
    footer: false,
});

// Set various template options for this layout variation
template.mainContent({ mode: "full" });
</script>

<template>
    <BaseLayout />
</template>
