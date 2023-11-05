<script setup>
import { computed, onMounted } from "vue";
import { useTemplateStore } from "@/stores/template.store";

// Import all layout partials
import BaseHeader from "@/layouts/partials/Header.vue";
import BaseSidebar from "@/layouts/partials/Sidebar.vue";
import BaseSideOverlay from "@/layouts/partials/SideOverlay.vue";
import BaseFooter from "@/layouts/partials/Footer.vue";

// Component properties
defineProps({
    sidebarWithMiniNav: {
        type: Boolean,
        default: false,
        description: "If the sidebar is in Mini Nav Mode",
    },
});

// Template store
const template = useTemplateStore();

// Set default color theme
template.setColorTheme({
    theme: template.settings.colorTheme,
});

// Render main classes based on store options
const classContainer = computed(() => {
    return {
        "sidebar-r": template.layout.sidebar && !template.settings.sidebarLeft,
        "sidebar-mini": template.layout.sidebar && template.settings.sidebarMini,
        "sidebar-o": template.layout.sidebar && template.settings.sidebarVisibleDesktop,
        "sidebar-o-xs": template.layout.sidebar && template.settings.sidebarVisibleMobile,
        "sidebar-dark":
            template.layout.sidebar &&
            template.settings.sidebarDark &&
            !template.settings.darkMode,
        "side-overlay-o":
            template.layout.sideOverlay && template.settings.sideOverlayVisible,
        "side-overlay-hover":
            template.layout.sideOverlay && template.settings.sideOverlayHoverable,
        "enable-page-overlay":
            template.layout.sideOverlay && template.settings.pageOverlay,
        "page-header-fixed": template.layout.header && template.settings.headerFixed,
        "page-header-dark":
            template.layout.header &&
            template.settings.headerDark &&
            !template.settings.darkMode,
        "main-content-boxed": template.settings.mainContent === "boxed",
        "main-content-narrow": template.settings.mainContent === "narrow",
        "rtl-support": template.settings.rtlSupport,
        "side-trans-enabled": template.settings.sideTransitions,
        "side-scroll": true,
        "sidebar-dark page-header-dark dark-mode": template.settings.darkMode,
    };
});

// Change dark mode based on dark mode system preference
if (template.settings.darkModeSystem) {
    if (
        window.matchMedia &&
        window.matchMedia("(prefers-color-scheme: dark)").matches
    ) {
        template.darkMode({ mode: "on" });
    } else {
        template.darkMode({ mode: "off" });
    }
}

window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", (e) => {
        if (template.settings.darkModeSystem) {
            if (e.matches) {
                template.darkMode({ mode: "on" });
            } else {
                template.darkMode({ mode: "off" });
            }
        }
    });

// Remove side transitions on window resizing
onMounted(() => {
    let winResize = false;

    window.addEventListener("resize", () => {
        clearTimeout(winResize);

        template.setSideTransitions({ transitions: false });

        winResize = setTimeout(() => {
            template.setSideTransitions({ transitions: true });
        }, 500);
    });
});
</script>

<template>
    <div id="page-container" :class="classContainer">
        <!-- Page Loader -->
        <div id="page-loader" :class="{ show: template.settings.pageLoader }"></div>

        <!-- Page Overlay -->
        <div id="page-overlay" v-if="template.layout.sideOverlay && template.settings.pageOverlay"
            @click="template.sideOverlay({ mode: 'close' })"></div>
        <!-- END Page Overlay -->

        <!-- Side Overlay -->
        <BaseSideOverlay v-if="template.layout.sideOverlay">
            <template #header>
                <slot name="side-overlay-header"></slot>
            </template>

            <template #content>
                <slot name="side-overlay-content"></slot>
            </template>

            <slot name="side-overlay"></slot>
        </BaseSideOverlay>
        <!-- END Side Overlay -->

        <!-- Sidebar -->
        <BaseSidebar v-if="template.layout.sidebar" :with-mini-nav="sidebarWithMiniNav">
            <template #header>
                <slot name="sidebar-header"></slot>
            </template>

            <template #header-extra>
                <slot name="sidebar-header-extra"></slot>
            </template>

            <template #content>
                <slot name="sidebar-content"></slot>
            </template>

            <slot name="sidebar"></slot>
        </BaseSidebar>
        <!-- END Sidebar -->

        <!-- Header -->
        <BaseHeader v-if="template.layout.header">
            <template #content-left>
                <slot name="header-content-left"></slot>
            </template>

            <template #content-right>
                <slot name="header-content-right"></slot>
            </template>

            <template #content>
                <slot name="header-content"></slot>
            </template>
            <slot name="header"></slot>
        </BaseHeader>
        <!-- END Header -->

        <!-- Main Container -->
        <div id="main-container">
            <slot name="page-top-content"></slot>
            <RouterView />
        </div>
        <!-- END Main Container -->

        <!-- Footer -->
        <BaseFooter v-if="template.layout.footer">
            <template #content-left>
                <slot name="footer-content-left"></slot>
            </template>

            <template #content-right>
                <slot name="footer-content-right"></slot>
            </template>
            <slot name="footer"></slot>
        </BaseFooter>
        <!-- END Footer -->
    </div>
</template>
