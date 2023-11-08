<script setup>
import { useTemplateStore } from '@/stores/template.store';
import { useAuthStore } from '@/stores/auth.store';

const template = useTemplateStore();
const auth = useAuthStore();
</script>

<template>
    <!-- Hero -->
    <div class="hero bg-body-extra-light">
        <div class="content content-full text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <i class="fa fa-2x fa-circle-notch text-primary"></i>
                    <h1 class="fw=bold mt-3 mb-2">
                        {{ template.app.name }}
                    </h1>
                    <p class="fs-lg fw-medium text-muted mb-4">
                        {{ template.app.description }}
                    </p>
                    <template v-if="auth.user">
                        <RouterLink :to="{ name: `${auth.user.role}.dashboard` }" class="btn btn-primary px-4 py-3"
                            v-click-ripple>
                            Dashboard
                            <i class="fa fa-fw fa-arrow-right ms-1 opacity-50"></i>
                        </RouterLink>
                    </template>
                    <template v-else>
                        <RouterLink :to="{ name: 'auth.signin' }" class="btn btn-primary px-4 py-3" v-click-ripple>
                            Sign in
                            <i class="fa fa-fw fa-arrow-right ms-1 opacity-50"></i>
                        </RouterLink>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->
</template>
