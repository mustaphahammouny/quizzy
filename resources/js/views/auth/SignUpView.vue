<script setup>
import { reactive, computed } from "vue";
import { useRouter } from "vue-router";

import LayoutAuth from "@/layouts/variations/Auth.vue";

// Vuelidate, for more info and examples you can check out https://github.com/vuelidate/vuelidate
import useVuelidate from "@vuelidate/core";
import { required, minLength, email, sameAs } from "@vuelidate/validators";

import http from "@/support/http";

// Router
const router = useRouter();

// Input state variables
const state = reactive({
    first_name: null,
    last_name: null,
    email: null,
    password: null,
    password_confirmation: null,
    role: 'student',
    terms: null,
});

// Validation rules
const rules = computed(() => {
    return {
        first_name: {
            required,
            minLength: minLength(3),
        },
        last_name: {
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
        password_confirmation: {
            required,
            sameAs: sameAs(state.password),
        },
        role: {
            required,

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

    const response = await http.post('register', state);

    console.log(response);
    return;

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
                <RouterLink :to="{ name: 'auth.signin' }"
                    class="text-primary fs-sm fw-medium d-block d-lg-inline-block mb-1">
                    Sign in now
                </RouterLink>
            </p>
        </div>
        <!-- END Header -->

        <!-- Sign Up Form -->
        <div class="row g-0 justify-content-center">
            <div class="col-sm-8 col-xl-4">
                <form @submit.prevent="onSubmit">
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="first-name" name="first-name" placeholder="first name"
                            :class="{ 'is-invalid': v$.first_name.$errors.length }" v-model="state.first_name"
                            @blur="v$.first_name.$touch" />
                        <label class="first-capitalize" for="first-name">first name</label>
                        <div v-if="v$.first_name.$errors.length" class="invalid-feedback animated fadeIn">
                            {{ v$.first_name.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="last-name" name="last-name" placeholder="last name"
                            :class="{ 'is-invalid': v$.last_name.$errors.length }" v-model="state.last_name"
                            @blur="v$.last_name.$touch" />
                        <label class="first-capitalize" for="last-name">last name</label>
                        <div v-if="v$.last_name.$errors.length" class="invalid-feedback animated fadeIn">
                            {{ v$.last_name.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <select class="form-select" id="role" name="role" aria-label="role"
                            :class="{ 'is-invalid': v$.role.$errors.length }" v-model="state.role">
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
                        <label class="first-capitalize" for="role">academic status</label>
                        <div v-if="v$.role.$errors.length" class="invalid-feedback animated fadeIn">
                            {{ v$.role.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="email" class="form-control" id="email" name="email" placeholder="email"
                            :class="{ 'is-invalid': v$.email.$errors.length }" v-model="state.email"
                            @blur="v$.email.$touch" />
                        <label class="first-capitalize" for="email">email</label>
                        <div v-if="v$.email.$errors.length" class="invalid-feedback animated fadeIn">
                            {{ v$.email.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="password"
                            :class="{ 'is-invalid': v$.password.$errors.length }" v-model="state.password"
                            @blur="v$.password.$touch" />
                        <label class="first-capitalize" for="password">password</label>
                        <div v-if="v$.password.$errors.length" class="invalid-feedback animated fadeIn">
                            {{ v$.password.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="password confirmation"
                            :class="{ 'is-invalid': v$.password_confirmation.$errors.length }"
                            v-model="state.password_confirmation" @blur="v$.password_confirmation.$touch" />
                        <label class="first-capitalize" for="password_confirmation">password confirmation</label>
                        <div v-if="v$.password_confirmation.$errors.length" class="invalid-feedback animated fadeIn">
                            {{ v$.password_confirmation.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-md-flex align-items-md-center justify-content-md-between">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" :class="{
                                    'is-invalid': v$.terms.$errors.length,
                                }" v-model="state.terms" @blur="v$.terms.$touch" />
                                <label class="form-check-label" for="terms">I agree to Terms &amp;
                                    Conditions</label>
                                <div v-if="v$.terms.$errors.length" class="invalid-feedback animated fadeIn">
                                    {{ v$.terms.$errors[0].$message }}
                                </div>
                            </div>
                            <div class="py-2">
                                <a class="fs-sm fw-medium" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#modal-terms">View Terms</a>
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
