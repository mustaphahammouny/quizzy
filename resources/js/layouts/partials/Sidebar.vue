<script setup>
import { ref, watch, onMounted } from "vue";

import { useTemplateStore } from "@/stores/template.store";
import { useAuthStore } from "@/stores/auth.store";

import BaseNavigation from "@/components/BaseNavigation.vue";

// SimpleBar, for more info and examples you can check out https://github.com/Grsmto/simplebar/tree/master/packages/simplebar-vue
import SimpleBar from "simplebar";

// Grab menu navigation arrays
import menu from "@/data/menu";

// Component properties
defineProps({
    withMiniNav: {
        type: Boolean,
        default: false,
        description: "If the sidebar is in Mini Nav Mode",
    },
});

const template = useTemplateStore();
const auth = useAuthStore();

const navigation = menu[auth.user.role];

// Dark Mode preference helper for radios
const radioDarkMode = ref();

// Sets default dark mode preferences for radios
function setDarkModeRadioDefault() {
    if (template.settings.darkModeSystem) {
        radioDarkMode.value = "system";
    } else {
        if (template.settings.darkMode) {
            radioDarkMode.value = "dark";
        } else {
            radioDarkMode.value = "light";
        }
    }
}

// When the user sets dark mode preference through the radios
function onDarkModeRadioChange() {
    if (radioDarkMode.value === "system") {
        template.darkModeSystem({ mode: "on" });
    } else {
        template.darkModeSystem({ mode: "off" });

        if (radioDarkMode.value === "dark") {
            template.darkMode({ mode: "on" });
        } else {
            template.darkMode({ mode: "off" });
        }
    }
}

// Set dark mode preference radios default and watch for changes to store
setDarkModeRadioDefault();
watch(
    () => template.settings.darkModeSystem,
    () => {
        setDarkModeRadioDefault();
    }
);
watch(
    () => template.settings.darkMode,
    () => {
        setDarkModeRadioDefault();
    }
);

// Init SimpleBar (custom scrolling)
onMounted(() => {
    new SimpleBar(document.getElementById("simplebar-sidebar"));
});
</script>

<template>
    <!-- Sidebar -->
    <!--
                Sidebar Mini Mode - Display Helper classes

                Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
                Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
                If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

                Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
                Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
                Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
              -->
    <nav id="sidebar" :class="{ 'with-mini-nav': withMiniNav }" aria-label="Main Navigation">
        <slot>
            <!-- Side Header -->
            <div class="content-header">
                <slot name="header">
                    <!-- Logo -->
                    <RouterLink :to="{ name: 'home' }" class="fw-semibold text-dual">
                        <span class="smini-visible">
                            <i class="fa fa-circle-notch text-primary"></i>
                        </span>
                        <span class="smini-hide fs-5 tracking-wider">
                            {{ template.app.name }}
                        </span>
                    </RouterLink>
                    <!-- END Logo -->
                </slot>

                <!-- Extra -->
                <div>
                    <slot name="header-extra">
                        <!-- Dark Mode -->
                        <div class="dropdown d-inline-block mx-1">
                            <button type="button" class="btn btn-sm btn-alt-secondary" id="sidebar-dark-mode-dropdown"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                                aria-expanded="false">
                                <i v-if="!template.settings.darkMode" class="far fa-moon"></i>
                                <i v-if="template.settings.darkMode" class="fa fa-moon"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu fs-sm smini-hide border-0"
                                style="min-width: 11rem" aria-labelledby="sidebar-dark-mode-dropdown">
                                <div class="px-3 py-2 space-y-2">
                                    <div class="form-check form-block">
                                        <input class="form-check-input" type="radio" id="radio-dark-mode-off" value="light"
                                            v-model="radioDarkMode" @change="onDarkModeRadioChange" />
                                        <label class="form-check-label fw-semibold" for="radio-dark-mode-off">
                                            <i class="fa fa-fw fa-sun opacity-50 me-2"></i>
                                            <span>Light</span>
                                        </label>
                                    </div>
                                    <div class="form-check form-block">
                                        <input class="form-check-input" type="radio" id="radio-dark-mode-on" value="dark"
                                            v-model="radioDarkMode" @change="onDarkModeRadioChange" />
                                        <label class="form-check-label fw-semibold" for="radio-dark-mode-on">
                                            <i class="fa fa-fw fa-moon opacity-50 me-2"></i>
                                            <span>Dark</span>
                                        </label>
                                    </div>
                                    <div class="form-check form-block mb-0">
                                        <input class="form-check-input" type="radio" id="radio-dark-mode-system"
                                            value="system" v-model="radioDarkMode" @change="onDarkModeRadioChange" />
                                        <label class="form-check-label fw-semibold" for="radio-dark-mode-system">
                                            <i class="fa fa-fw fa-desktop opacity-50 me-2"></i>
                                            <span>System</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END Dark Mode -->

                        <!-- Options -->
                        <div class="dropdown d-inline-block ms-1">
                            <button type="button" class="btn btn-sm btn-alt-secondary" id="sidebar-themes-dropdown"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fa fa-brush"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end fs-sm smini-hide border-0"
                                aria-labelledby="sidebar-themes-dropdown">
                                <!-- Color Themes -->
                                <button type="button"
                                    class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                                    @click="template.setColorTheme({ theme: '' })">
                                    <span>Default</span>
                                    <i class="fa fa-circle text-default"></i>
                                </button>
                                <button type="button"
                                    class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                                    @click="template.setColorTheme({ theme: 'amethyst' })">
                                    <span>Amethyst</span>
                                    <i class="fa fa-circle text-amethyst"></i>
                                </button>
                                <button type="button"
                                    class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                                    @click="template.setColorTheme({ theme: 'city' })">
                                    <span>City</span>
                                    <i class="fa fa-circle text-city"></i>
                                </button>
                                <button type="button"
                                    class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                                    @click="template.setColorTheme({ theme: 'flat' })">
                                    <span>Flat</span>
                                    <i class="fa fa-circle text-flat"></i>
                                </button>
                                <button type="button"
                                    class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                                    @click="template.setColorTheme({ theme: 'modern' })">
                                    <span>Modern</span>
                                    <i class="fa fa-circle text-modern"></i>
                                </button>
                                <button type="button"
                                    class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                                    @click="template.setColorTheme({ theme: 'smooth' })">
                                    <span>Smooth</span>
                                    <i class="fa fa-circle text-smooth"></i>
                                </button>
                                <!-- END Color Themes -->

                                <div v-if="!template.settings.darkMode">
                                    <div class="dropdown-divider"></div>

                                    <!-- Sidebar Styles -->
                                    <button type="button" class="dropdown-item fw-medium"
                                        @click="template.sidebarStyle({ mode: 'light' })">
                                        <span>Sidebar Light</span>
                                    </button>
                                    <button type="button" class="dropdown-item fw-medium"
                                        @click="template.sidebarStyle({ mode: 'dark' })">
                                        <span>Sidebar Dark</span>
                                    </button>
                                    <!-- END Sidebar Styles -->

                                    <div class="dropdown-divider"></div>

                                    <!-- Header Styles -->
                                    <button type="button" class="dropdown-item fw-medium"
                                        @click="template.headerStyle({ mode: 'light' })">
                                        <span>Header Light</span>
                                    </button>
                                    <button type="button" class="dropdown-item fw-medium"
                                        @click="template.headerStyle({ mode: 'dark' })">
                                        <span>Header Dark</span>
                                    </button>
                                    <!-- END Header Styles -->
                                </div>
                            </div>
                        </div>
                        <!-- END Options -->
                    </slot>

                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <button type="button" class="d-lg-none btn btn-sm btn-alt-secondary ms-1"
                        @click="template.sidebar({ mode: 'close' })">
                        <i class="fa fa-fw fa-times"></i>
                    </button>
                    <!-- END Close Sidebar -->
                </div>
                <!-- END Extra -->
            </div>
            <!-- END Side Header -->

            <!-- Sidebar Scrolling -->
            <div id="simplebar-sidebar" class="js-sidebar-scroll">
                <slot name="content">
                    <!-- Side Navigation -->
                    <div class="content-side">
                        <BaseNavigation :nodes="navigation" />
                    </div>
                    <!-- END Side Navigation -->
                </slot>
            </div>
            <!-- END Sidebar Scrolling -->
        </slot>
    </nav>
    <!-- END Sidebar -->
</template>
