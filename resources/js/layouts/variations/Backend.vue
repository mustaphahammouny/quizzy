<script setup>
import { useTemplateStore } from "@/stores/template.store";

import BaseLayout from "@/layouts/BaseLayout.vue";

// Template store
const template = useTemplateStore();

// Set default elements for this layout
template.setLayout({
    header: true,
    sidebar: true,
    sideOverlay: false,
    footer: true,
});

// Set various template options for this layout variation
template.headerStyle({ mode: "light" });
template.mainContent({ mode: "narrow" });
</script>

<template>
    <BaseLayout />
</template>
