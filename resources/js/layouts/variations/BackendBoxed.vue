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
                <!-- User Dropdown -->
                <div class="dropdown d-inline-block ms-2">
                    <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                        id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle" src="/assets/media/avatars/avatar10.jpg" alt="Header Avatar"
                            style="width: 21px" />
                        <span class="d-none d-sm-inline-block ms-2">{{ auth.user?.full_name ?? '' }}</span>
                        <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
                        aria-labelledby="page-header-user-dropdown">
                        <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                            <img class="img-avatar img-avatar48 img-avatar-thumb" src="/assets/media/avatars/avatar10.jpg"
                                alt="Header Avatar" />
                            <p class="mt-2 mb-0 fw-medium">{{ auth.user?.full_name ?? '' }}</p>
                            <p class="mb-0 text-muted fs-sm fw-medium">{{ auth.user?.role ?? '' }}</p>
                        </div>
                        <div class="p-2">
                            <RouterLink :to="{ name: 'home' }"
                                class="dropdown-item d-flex align-items-center justify-content-between">
                                <span class="fs-sm fw-medium">Profile</span>
                                <i class="fa fa-user ms-2"></i>
                            </RouterLink>
                        </div>
                        <div role="separator" class="dropdown-divider m-0"></div>
                        <div class="p-2">
                            <button v-on:click="logout"
                                class="dropdown-item d-flex align-items-center justify-content-between">
                                <span class="fs-sm fw-medium">Sign Out</span>
                                <i class="fa fa-sign-out ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- END User Dropdown -->
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
