<script setup>
import { computed, reactive } from "vue";

import useVuelidate from "@vuelidate/core";
import { required, minLength, email } from "@vuelidate/validators";

import { useAuthStore } from "@/stores/auth.store";

import alert from "@/support/alert";

const auth = useAuthStore();

const state = reactive({
    first_name: auth.user.first_name,
    last_name: auth.user.last_name,
    email: auth.user.email,
});

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
    };
});

const v$ = useVuelidate(rules, state);

const save = async () => {
    const result = await v$.value.$validate();

    if (!result) {
        return;
    }

    try {
        await http.put("user/profile-information", state);

        alert.show({ title: "Profile updated successfully!", icon: "success" });

        auth.attempt();
    } catch (error) {
        console.log(error.response.data.message);
    }
};
</script>

<template>
    <BaseBlock title="Update Profile Information" content-full>
        <p>Update your account's profile information and email address.</p>

        <div class="row items-push">
            <div class="col-md-8 col-lg-7 mx-auto">
                <form @submit.prevent="save" class="w-100">
                    <div class="form-floating mb-4">
                        <input
                            type="text"
                            class="form-control"
                            id="first_name"
                            name="first_name"
                            placeholder="First name"
                            :class="{
                                'is-invalid': v$.first_name.$errors.length,
                            }"
                            v-model="state.first_name"
                            @blur="v$.first_name.$touch"
                        />
                        <label class="first-capitalize" for="first_name">
                            First name
                        </label>
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
                            id="last_name"
                            name="last_name"
                            placeholder="Last name"
                            :class="{
                                'is-invalid': v$.last_name.$errors.length,
                            }"
                            v-model="state.last_name"
                            @blur="v$.last_name.$touch"
                        />
                        <label class="first-capitalize" for="last_name">
                            Last name
                        </label>
                        <div
                            v-if="v$.last_name.$errors.length"
                            class="invalid-feedback animated fadeIn"
                        >
                            {{ v$.last_name.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Email"
                            :class="{
                                'is-invalid': v$.email.$errors.length,
                            }"
                            v-model="state.email"
                            @blur="v$.email.$touch"
                        />
                        <label class="first-capitalize" for="email">
                            Email
                        </label>
                        <div
                            v-if="v$.email.$errors.length"
                            class="invalid-feedback animated fadeIn"
                        >
                            {{ v$.email.$errors[0].$message }}
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
