<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useTemplateStore } from "@/stores/template.store";
import { useAuthStore } from "@/stores/auth.store";

import BaseLayout from "@/layouts/BaseLayout.vue";
import BaseNavigation from "@/components/BaseNavigation.vue";

// Grab menu navigation arrays
import menu from "@/data/menu";

const router = useRouter();
const template = useTemplateStore();
const auth = useAuthStore();

const navigation = menu[auth.user.role];

// Reactive variables
const mobileNav = ref(false);

// Set default elements for this layout
template.setLayout({
    header: true,
    sidebar: false,
    sideOverlay: false,
    footer: true,
});

// Set various template options for this layout variation
template.headerStyle({ mode: "dark" });
template.mainContent({ mode: "boxed" });

const logout = async () => {
    try {
        await auth.logout();

        router.push({ name: 'auth.signin' });
    } catch (error) {
        console.log(error.message);
    }
};
</script>

<template>
    <BaseLayout>
        <!-- Header Content -->
        <!-- Using the available v-slot, we can override the default Header content from layouts/partials/Header.vue -->
        <template #header-content>
            <!-- Left Section -->
            <div class="d-flex align-items-center">
                <!-- Logo -->
                <RouterLink :to="{ name: 'home' }" class="fw-semibold fs-5 tracking-wider text-dual me-3">
                    {{ template.app.name }}
                </RouterLink>
                <!-- END Logo -->
            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div class="d-flex align-items-center">
                <ul class="nav nav-main-dark nav-main-hover">
                    <li class="nav-main-item">
                        <RouterLink v-if="auth.user" :to="{ name: `${auth.user.role}.profile.edit`}" class="nav-main-link">
                            <i class="nav-main-link-icon fa fa-user"></i>
                            <span class="nav-main-link-name">Profile</span>
                        </RouterLink>
                    </li>
                    <li class="nav-main-item">
                        <a role="button" href="javascript:void(0)" v-on:click="logout" class="nav-main-link">
                            <i class="nav-main-link-icon fa fa-sign-out"></i>
                            <span class="nav-main-link-name">Sign Out</span>
                        </a>
                    </li>
                </ul>

            </div>
            <!-- END Right Section -->
        </template>
        <!-- END Header Content -->

        <!-- Page Top Content -->
        <!-- Using the available v-slot, we can override the default Page Top Content from layouts/BaseLayout.vue -->
        <template #page-top-content>
            <div class="bg-primary-darker">
                <div class="bg-black-10">
                    <div class="content py-3">
                        <!-- Toggle Navigation -->
                        <div class="d-lg-none">
                            <button type="button"
                                class="btn w-100 btn-alt-secondary d-flex justify-content-between align-items-center"
                                @click="
                                    () => {
                                        mobileNav = !mobileNav;
                                    }
                                ">
                                Menu
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>
                        <!-- END Toggle Navigation -->

                        <!-- Navigation -->
                        <div id="main-navigation" class="d-lg-block mt-2 mt-lg-0" :class="{
                            'd-none': !mobileNav,
                        }">
                            <BaseNavigation :nodes="navigation" horizontal horizontal-hover dark />
                        </div>
                        <!-- END Navigation -->
                    </div>
                </div>
            </div>
        </template>
        <!-- END Page Top Content -->
    </BaseLayout>
</template>
