<script setup>
// Component properties
defineProps({
    title: {
        type: String,
        description: "The title of page heading section",
    },
    subtitle: {
        type: String,
        description: "The subtitle of page heading section",
    },
});
</script>

<template>
    <div class="bg-body-light">
        <div class="content content-full py-3">
            <slot>
                <div v-if="title || $slots.extra"
                    class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <div class="flex-grow-1">
                        <h1 v-if="title" class="h4 fw-semibold mb-1">{{ title }}</h1>
                        <h2 v-if="subtitle" class="fs-base lh-base fw-medium text-muted mb-0">
                            {{ subtitle }}
                        </h2>
                    </div>
                    <div v-if="$slots.extra" class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3">
                        <slot name="extra"></slot>
                    </div>
                </div>
            </slot>
        </div>
    </div>
</template>
