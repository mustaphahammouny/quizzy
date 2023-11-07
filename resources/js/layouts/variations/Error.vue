<script setup>
import { useTemplateStore } from "@/stores/template.store";

// Template store
const template = useTemplateStore();
</script>

<template>
    <!-- Page Content -->
    <div class="hero">
        <div class="hero-inner text-center">
            <div class="bg-body-extra-light">
                <div class="content content-full overflow-hidden">
                    <div class="py-4">
                        <slot></slot>
                    </div>

                    <RouterLink :to="{ name: 'home' }" class="btn btn-dark">
                        <i class="fas fa-home me-2"></i>
                        <span>Go Back to Home</span>
                    </RouterLink>
                </div>
            </div>
            <div class="content content-full text-muted fs-sm fw-medium">
                <RouterLink :to="{ name: 'home' }" class="fw-semibold">
                    {{ `${template.app.name} ${template.app.version}` }}
                </RouterLink>
                &copy; {{ template.app.copyright }}
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</template>
