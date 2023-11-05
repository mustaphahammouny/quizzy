<script setup>
import { reactive, computed } from "vue";
import { useRouter } from "vue-router";

import LayoutAuth from "@/layouts/variations/Auth.vue";

// Vuelidate, for more info and examples you can check out https://github.com/vuelidate/vuelidate
import useVuelidate from "@vuelidate/core";
import { required, minLength } from "@vuelidate/validators";

// Router
const router = useRouter();

// Input state variables
const state = reactive({
    reminder: null,
});

// Validation rules
const rules = computed(() => {
    return {
        reminder: {
            required,
            minLength: minLength(3),
        },
    };
});

// Use vuelidate
const v$ = useVuelidate(rules, state);

// On form submission
async function onSubmit() {
    const result = await v$.value.$validate();

    if (!result) {
        // notify user form is invalid
        return;
    }

    // Go to dashboard
    router.push({ name: "backend-pages-auth" });
}
</script>

<template>
    <LayoutAuth>
        <!-- Header -->
        <div class="text-center mb-5">
            <p class="mb-3">
                <i class="fa fa-2x fa-circle-notch text-primary-light"></i>
            </p>
            <h1 class="fw-bold mb-2">Password Reminder</h1>
            <p class="fw-medium text-muted">
                Don't have an account?
                <RouterLink :to="{ name: 'auth.signup' }"
                    class="text-primary fs-sm fw-medium d-block d-lg-inline-block mb-1">
                    Sign up now
                </RouterLink>
            </p>
        </div>
        <!-- END Header -->

        <!-- Sign Up Form -->
        <div class="row g-0 justify-content-center">
            <div class="col-sm-8 col-xl-4">
                <form @submit.prevent="onSubmit">
                    <div class="mb-4">
                        <input type="text" class="form-control form-control-lg form-control-alt py-3"
                            id="reminder-credential" name="reminder-credential" placeholder="Email" :class="{
                                'is-invalid': v$.reminder.$errors.length,
                            }" v-model="state.reminder" @blur="v$.reminder.$touch" />
                        <div v-if="v$.reminder.$errors.length" class="invalid-feedback animated fadeIn">
                            Please enter a valid credential
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-lg btn-alt-primary">
                            <i class="fa fa-fw fa-envelope me-1 opacity-50"></i>
                            Send Mail
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Sign Up Form -->
    </LayoutAuth>
</template>
