<script setup>
import { reactive, computed, ref, watch } from "vue";
import { useRouter } from "vue-router";

// Vuelidate, for more info and examples you can check out https://github.com/vuelidate/vuelidate
import useVuelidate from "@vuelidate/core";
import { required, minLength } from "@vuelidate/validators";

import LayoutAuth from "@/layouts/variations/Auth.vue";
import Alert from "@/components/Alert.vue";

import { useAuthStore } from "@/stores/auth.store";

const router = useRouter();
const auth = useAuthStore();

// Input state variables
const state = reactive({
    email: null,
    password: null,
});

const errorMessage = ref("");
const profile = ref(null);
const profiles = ref([
    {
        label: "Teacher",
        value: "teacher",
        email: "teacher@quizzy.com",
        password: "password",
    },
    {
        label: "Student",
        value: "student",
        email: "student@quizzy.com",
        password: "password",
    },
]);

// Validation rules
const rules = computed(() => {
    return {
        email: {
            required,
            minLength: minLength(3),
        },
        password: {
            required,
            minLength: minLength(8),
        },
    };
});

watch(profile, (newProfile, oldProfile) => {
    state.email = newProfile?.email ?? null;
    state.password = newProfile?.password ?? null;
});

// Use vuelidate
const v$ = useVuelidate(rules, state);

// On form submission
const login = async () => {
    errorMessage.value = "";

    const result = await v$.value.$validate();

    if (!result) {
        // notify user form is invalid
        return;
    }

    try {
        await auth.login(state);

        router.push({ name: `${auth.user.role}.dashboard` });
    } catch (error) {
        errorMessage.value = error.message;
    }
};
</script>

<template>
    <LayoutAuth>
        <!-- Header -->
        <div class="text-center mb-5">
            <p class="mb-3">
                <i class="fa fa-2x fa-circle-notch text-primary-light"></i>
            </p>
            <h1 class="fw-bold mb-2">Sign In</h1>
            <p class="fw-medium text-muted">
                Don't have an account?
                <RouterLink
                    :to="{ name: 'auth.signup' }"
                    class="text-primary fs-sm fw-medium d-block d-lg-inline-block mb-1"
                >
                    Sign up now
                </RouterLink>
            </p>
        </div>
        <!-- END Header -->

        <!-- Sign Up Form -->
        <div class="row g-0 justify-content-center">
            <div class="col-sm-8 col-xl-4">
                <Alert
                    v-if="errorMessage"
                    variant="danger"
                    icon="fa-times-circle"
                    :message="errorMessage"
                />

                <form @submit.prevent="login">
                    <div class="mb-4">
                        <VSelect
                            placeholder="Sign in as"
                            :options="profiles"
                            v-model="profile"
                            @change="
                                (val) => {
                                    console.log(val);
                                }
                            "
                        >
                        </VSelect>
                    </div>
                    <div class="form-floating mb-4">
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="email"
                            :class="{ 'is-invalid': v$.email.$errors.length }"
                            v-model="state.email"
                            @blur="v$.email.$touch"
                        />
                        <label class="first-capitalize" for="email"
                            >email</label
                        >
                        <div
                            v-if="v$.email.$errors.length"
                            class="invalid-feedback animated fadeIn"
                        >
                            {{ v$.email.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="password"
                            :class="{
                                'is-invalid': v$.password.$errors.length,
                            }"
                            v-model="state.password"
                            @blur="v$.password.$touch"
                        />
                        <label class="first-capitalize" for="password"
                            >password</label
                        >
                        <div
                            v-if="v$.password.$errors.length"
                            class="invalid-feedback animated fadeIn"
                        >
                            {{ v$.password.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="mb-4">
                        <div
                            class="d-md-flex align-items-md-center justify-content-md-between"
                        >
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                />
                                <label class="form-check-label" for="remember"
                                    >Remember me</label
                                >
                            </div>
                            <div class="py-2">
                                <RouterLink
                                    :to="{ name: 'auth.reminder' }"
                                    class="text-primary fs-sm fw-medium d-block d-lg-inline-block mb-1"
                                >
                                    Forgot Password?
                                </RouterLink>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button
                            type="submit"
                            class="btn btn-lg btn-alt-success"
                        >
                            <i class="fa fa-fw fa-plus me-1 opacity-50"></i>
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Sign Up Form -->
    </LayoutAuth>
</template>
