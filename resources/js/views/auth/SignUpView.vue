<script setup>
import { reactive, computed, ref, onBeforeMount } from "vue";
import { useRouter } from "vue-router";

// Vuelidate, for more info and examples you can check out https://github.com/vuelidate/vuelidate
import useVuelidate from "@vuelidate/core";
import { required, minLength, email, sameAs } from "@vuelidate/validators";

import LayoutAuth from "@/layouts/variations/Auth.vue";
import Alert from "@/components/Alert.vue";

import { useAuthStore } from "@/stores/auth.store";

const router = useRouter();
const auth = useAuthStore();

const roles = ref([]);
const errorMessage = ref("");

// Input state variables
const state = reactive({
    first_name: null,
    last_name: null,
    email: null,
    password: null,
    password_confirmation: null,
    role: null,
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
            minLength: minLength(8),
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
const register = async () => {
    errorMessage.value = "";

    const result = await v$.value.$validate();

    if (!result) {
        return;
    }

    try {
        await auth.register(state);

        router.push({ name: `${auth.user.role}.dashboard` });
    } catch (error) {
        errorMessage.value = error.message;
    }
};

onBeforeMount(async () => {
    try {
        const response = await http.get("api/roles/select");

        roles.value = response.data.data;
    } catch (error) {
        console.log(error.response.data.message);
    }
});
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
                <RouterLink
                    :to="{ name: 'auth.signin' }"
                    class="text-primary fs-sm fw-medium d-block d-lg-inline-block mb-1"
                >
                    Sign in now
                </RouterLink>
            </p>
        </div>
        <!-- END Header -->

        <!-- Sign Up Form -->
        <div class="row g-0 justify-content-center">
            <div class="col-sm-8 col-xl-4">
                <form @submit.prevent="register">
                    <Alert
                        v-if="errorMessage"
                        variant="danger"
                        icon="fa-times-circle"
                        :message="errorMessage"
                    />

                    <div class="form-floating mb-4">
                        <input
                            type="text"
                            class="form-control"
                            id="first-name"
                            name="first-name"
                            placeholder="first name"
                            :class="{
                                'is-invalid': v$.first_name.$errors.length,
                            }"
                            v-model="state.first_name"
                            @blur="v$.first_name.$touch"
                        />
                        <label class="first-capitalize" for="first-name"
                            >first name</label
                        >
                        <div
                            v-if="v$.first_name.$errors.length"
                            class="invalid-feedback animated fadeIn"
                        >
                            {{ v$.first_name.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <input
                            type="text"
                            class="form-control"
                            id="last-name"
                            name="last-name"
                            placeholder="last name"
                            :class="{
                                'is-invalid': v$.last_name.$errors.length,
                            }"
                            v-model="state.last_name"
                            @blur="v$.last_name.$touch"
                        />
                        <label class="first-capitalize" for="last-name"
                            >last name</label
                        >
                        <div
                            v-if="v$.last_name.$errors.length"
                            class="invalid-feedback animated fadeIn"
                        >
                            {{ v$.last_name.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="mb-4">
                        <VSelect
                            placeholder="Academic status"
                            :options="roles"
                            :reduce="(role) => role.value"
                            :class="{ 'is-invalid': v$.role.$errors.length }"
                            v-model="state.role"
                        ></VSelect>
                        <div
                            v-if="v$.role.$errors.length"
                            class="invalid-feedback animated fadeIn"
                        >
                            {{ v$.role.$errors[0].$message }}
                        </div>
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
                    <div class="form-floating mb-4">
                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="password confirmation"
                            :class="{
                                'is-invalid':
                                    v$.password_confirmation.$errors.length,
                            }"
                            v-model="state.password_confirmation"
                            @blur="v$.password_confirmation.$touch"
                        />
                        <label
                            class="first-capitalize"
                            for="password_confirmation"
                            >password confirmation</label
                        >
                        <div
                            v-if="v$.password_confirmation.$errors.length"
                            class="invalid-feedback animated fadeIn"
                        >
                            {{ v$.password_confirmation.$errors[0].$message }}
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
                                    id="terms"
                                    name="terms"
                                    :class="{
                                        'is-invalid': v$.terms.$errors.length,
                                    }"
                                    v-model="state.terms"
                                    @blur="v$.terms.$touch"
                                />
                                <label class="form-check-label" for="terms"
                                    >I agree to Terms &amp; Conditions</label
                                >
                                <div
                                    v-if="v$.terms.$errors.length"
                                    class="invalid-feedback animated fadeIn"
                                >
                                    {{ v$.terms.$errors[0].$message }}
                                </div>
                            </div>
                            <div class="py-2">
                                <a
                                    class="fs-sm fw-medium"
                                    href="javascript:void(0)"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-terms"
                                    >View Terms</a
                                >
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button
                            type="submit"
                            class="btn btn-lg btn-alt-success"
                        >
                            <i class="fa fa-fw fa-plus me-1 opacity-50"></i>
                            Sign Up
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Sign Up Form -->
    </LayoutAuth>
</template>
