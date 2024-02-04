<script setup>
import { computed, reactive, ref } from "vue";

import useVuelidate from "@vuelidate/core";
import { required, minLength, sameAs } from "@vuelidate/validators";

import Alert from "@/components/Alert.vue";

import alert from "@/support/alert";

const state = reactive({
    current_password: null,
    password_confirmation: null,
    password: null,
});

const errorMessage = ref("");

const rules = computed(() => {
    return {
        current_password: {
            required,
            minLength: minLength(8),
        },
        password: {
            required,
            minLength: minLength(8),
        },
        password_confirmation: {
            required,
            minLength: minLength(8),
            sameAs: sameAs(state.password),
        },
    };
});

const v$ = useVuelidate(rules, state);

const resetForm = () => {
    v$.value.$reset();

    state.current_password = null;
    state.password_confirmation = null;
    state.password = null;
};

const save = async () => {
    errorMessage.value = "";

    const result = await v$.value.$validate();

    if (!result) {
        return;
    }

    try {
        await http.put("user/password", state);

        alert.show({
            title: "Password updated successfully!",
            icon: "success",
        });

        resetForm();
    } catch (error) {
        errorMessage.value = error.response.data.message;
    }
};
</script>

<template>
    <BaseBlock title="Update password" content-full>
        <p>
            Ensure your account is using a long, random password to stay secure.
        </p>

        <div class="row items-push">
            <div class="col-md-8 col-lg-7 mx-auto">
                <form @submit.prevent="save" class="w-100">
                    <Alert
                        v-if="errorMessage"
                        variant="danger"
                        icon="fa-times-circle"
                        :message="errorMessage"
                    />

                    <div class="form-floating mb-4">
                        <input
                            type="password"
                            class="form-control"
                            id="current_password"
                            name="current_password"
                            placeholder="Current password"
                            :class="{
                                'is-invalid':
                                    v$.current_password.$errors.length,
                            }"
                            v-model="state.current_password"
                            @blur="v$.current_password.$touch"
                        />
                        <label class="first-capitalize" for="current_password">
                            Current password
                        </label>
                        <div
                            v-if="v$.current_password.$errors.length"
                            class="invalid-feedback animated fadeIn"
                        >
                            {{ v$.current_password.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Password"
                            :class="{
                                'is-invalid': v$.password.$errors.length,
                            }"
                            v-model="state.password"
                            @blur="v$.password.$touch"
                        />
                        <label class="first-capitalize" for="password">
                            Password
                        </label>
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
                        >
                            password confirmation
                        </label>
                        <div
                            v-if="v$.password_confirmation.$errors.length"
                            class="invalid-feedback animated fadeIn"
                        >
                            {{ v$.password_confirmation.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="text-center">
                        <button
                            type="submit"
                            class="btn btn-lg btn-alt-primary"
                        >
                            <i class="fa fa-fw fa-save me-1 opacity-50"></i>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </BaseBlock>
</template>
