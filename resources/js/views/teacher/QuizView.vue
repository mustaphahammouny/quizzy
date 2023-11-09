<script setup>
import { reactive, computed } from "vue";
import { useRouter } from "vue-router";

import useVuelidate from "@vuelidate/core";
import { required, minLength } from "@vuelidate/validators";

import http from '@/support/http';

const router = useRouter();

const state = reactive({
    name: null,
    active: false,
});

const rules = computed(() => {
    return {
        name: {
            required,
            minLength: minLength(3),
        },
    };
});

const v$ = useVuelidate(rules, state);

const createQuiz = async () => {
    const result = await v$.value.$validate();

    if (!result) {
        return;
    }

    try {
        await http.post("api/teacher/quizzes", state);

        router.push({ name: `teacher.quizzes.index` });
    } catch (e) {
        console.log(e.response.data.message);
    }
};
</script>

<template>
    <BasePageHeading title="Create quiz" />

    <div class="content">
        <BaseBlock content-full>
            <form @submit.prevent="createQuiz">
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="name" name="name" placeholder="name"
                        :class="{ 'is-invalid': v$.name.$errors.length }" v-model="state.name" @blur="v$.name.$touch" />
                    <label class="first-capitalize" for="name">name</label>
                    <div v-if="v$.name.$errors.length" class="invalid-feedback animated fadeIn">
                        {{ v$.name.$errors[0].$message }}
                    </div>
                </div>
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active" name="active" v-model="state.active">
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-alt-success">
                        <i class="fa fa-fw fa-plus me-1 opacity-50"></i> Create
                    </button>
                </div>
            </form>
        </BaseBlock>
    </div>
</template>
