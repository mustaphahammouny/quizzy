<script setup>
import { useRouter } from "vue-router";

import { useTemplateStore } from "@/stores/template.store";
import { useAuthStore } from "@/stores/auth.store";

const router = useRouter();
const template = useTemplateStore();
const auth = useAuthStore();

const logout = async () => {
    try {
        await auth.logout();

        router.push({ name: 'auth.signin' });
    } catch (e) {
        console.log(e.message);
    }
};
</script>

<template>
    <!-- Header -->
    <header id="page-header">
        <slot>
            <!-- Header Content -->
            <div class="content-header">
                <slot name="content">
                    <!-- Left Section -->
                    <div class="d-flex align-items-center">
                        <slot name="content-left">
                            <!-- Toggle Sidebar -->
                            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none"
                                @click="template.sidebar({ mode: 'toggle' })">
                                <i class="fa fa-fw fa-bars"></i>
                            </button>
                            <!-- END Toggle Sidebar -->

                            <!-- Toggle Mini Sidebar -->
                            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-none d-lg-inline-block"
                                @click="template.sidebarMini({ mode: 'toggle' })">
                                <i class="fa fa-fw fa-ellipsis-v"></i>
                            </button>
                            <!-- END Toggle Mini Sidebar -->
                        </slot>
                    </div>
                    <!-- END Left Section -->

                    <!-- Right Section -->
                    <div class="d-flex align-items-center">
                        <slot name="content-right">
                            <!-- User Dropdown -->
                            <div class="dropdown d-inline-block ms-2">
                                <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <img class="rounded-circle" src="/assets/media/avatars/avatar10.jpg" alt="Header Avatar"
                                        style="width: 21px" />
                                    <span class="d-none d-sm-inline-block ms-2">{{ auth.user?.full_name ?? '' }}</span>
                                    <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
                                    aria-labelledby="page-header-user-dropdown">
                                    <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                                        <img class="img-avatar img-avatar48 img-avatar-thumb"
                                            src="/assets/media/avatars/avatar10.jpg" alt="Header Avatar" />
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
                        </slot>
                    </div>
                    <!-- END Right Section -->
                </slot>
            </div>
            <!-- END Header Content -->

            <!-- Header Loader -->
            <div id="page-header-loader" class="overlay-header bg-body-extra-light"
                :class="{ show: template.settings.headerLoader }">
                <div class="content-header">
                    <div class="w-100 text-center">
                        <i class="fa fa-fw fa-circle-notch fa-spin"></i>
                    </div>
                </div>
            </div>
            <!-- END Header Loader -->
        </slot>
    </header>
    <!-- END Header -->
</template>
