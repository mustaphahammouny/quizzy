<script setup>
import { reactive, computed } from "vue";
import { useRouter } from "vue-router";

import LayoutAuth from "@/layouts/variations/Auth.vue";

// Vuelidate, for more info and examples you can check out https://github.com/vuelidate/vuelidate
import useVuelidate from "@vuelidate/core";
import { required, minLength, email, sameAs } from "@vuelidate/validators";

// Router
const router = useRouter();

// Input state variables
const state = reactive({
    username: null,
    email: null,
    password: null,
    confirmPassword: null,
    terms: null,
});

// Validation rules
const rules = computed(() => {
    return {
        username: {
            required,
            minLength: minLength(3),
        },
        email: {
            required,
            email,
        },
        password: {
            required,
            minLength: minLength(5),
        },
        confirmPassword: {
            required,
            sameAs: sameAs(state.password),
        },
        terms: {
            sameAs: sameAs(true),
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
            <h1 class="fw-bold mb-2">Sign Up</h1>
            <p class="fw-medium text-muted">
                Already have an account?
                <RouterLink :to="{ name: 'auth.signin' }" class="text-primary fs-sm fw-medium d-block d-lg-inline-block mb-1">
                    Sign in now
                </RouterLink>
            </p>
        </div>
        <!-- END Header -->

        <!-- Sign Up Form -->
        <div class="row g-0 justify-content-center">
            <div class="col-sm-8 col-xl-4">
                <form @submit.prevent="onSubmit">
                    <div class="mb-4">
                        <input type="text" class="form-control form-control-lg form-control-alt py-3" id="signup-username"
                            name="signup-username" placeholder="Username" :class="{
                                'is-invalid': v$.username.$errors.length,
                            }" v-model="state.username" @blur="v$.username.$touch" />
                        <div v-if="v$.username.$errors.length" class="invalid-feedback animated fadeIn">
                            Please enter a username
                        </div>
                    </div>
                    <div class="mb-4">
                        <input type="email" class="form-control form-control-lg form-control-alt py-3" id="signup-email"
                            name="signup-email" placeholder="Email" :class="{
                                'is-invalid': v$.email.$errors.length,
                            }" v-model="state.email" @blur="v$.email.$touch" />
                        <div v-if="v$.email.$errors.length" class="invalid-feedback animated fadeIn">
                            Please enter a valid email address
                        </div>
                    </div>
                    <div class="mb-4">
                        <input type="password" class="form-control form-control-lg form-control-alt py-3"
                            id="signup-password" name="signup-password" placeholder="Password" :class="{
                                'is-invalid': v$.password.$errors.length,
                            }" v-model="state.password" @blur="v$.password.$touch" />
                        <div v-if="v$.password.$errors.length" class="invalid-feedback animated fadeIn">
                            Please provide a password
                        </div>
                    </div>
                    <div class="mb-4">
                        <input type="password" class="form-control form-control-lg form-control-alt py-3"
                            id="signup-password-confirm" name="signup-password-confirm" placeholder="Confirm Password"
                            :class="{
                                'is-invalid': v$.confirmPassword.$errors.length,
                            }" v-model="state.confirmPassword" @blur="v$.confirmPassword.$touch" />
                        <div v-if="v$.confirmPassword.$errors.length" class="invalid-feedback animated fadeIn">
                            Please confirm the password
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-md-flex align-items-md-center justify-content-md-between">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="signup-terms" name="signup-terms"
                                    :class="{
                                        'is-invalid': v$.terms.$errors.length,
                                    }" v-model="state.terms" @blur="v$.terms.$touch" />
                                <label class="form-check-label" for="signup-terms">I agree to Terms &amp;
                                    Conditions</label>
                                <div v-if="v$.terms.$errors.length" class="invalid-feedback animated fadeIn">
                                    You must agree to the service terms!
                                </div>
                            </div>
                            <div class="py-2">
                                <a class="fs-sm fw-medium" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#one-signup-terms">View Terms</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-lg btn-alt-success">
                            <i class="fa fa-fw fa-plus me-1 opacity-50"></i> Sign Up
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Sign Up Form -->
    </LayoutAuth>
</template>
