<script setup>
import { reactive, computed, onBeforeMount } from "vue";
import { useRouter, useRoute } from "vue-router";

import useVuelidate from "@vuelidate/core";
import { required, minLength, numeric } from "@vuelidate/validators";

const router = useRouter();
const route = useRoute();

const state = reactive({
    quiz_id: route.params.quizId,
    question: null,
    type: null,
    time: null,
});

const rules = computed(() => {
    return {
        question: {
            required,
            minLength: minLength(3),
        },
        type: {
            required,
            numeric,
        },
        time: {
            required,
            numeric,
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
        await http.post(`api/teacher/questions`, state);

        router.push({ name: 'teacher.quizzes.edit', params: { id: route.params.quizId } });
    } catch (error) {
        console.log(error.response.data.message);
    }
};

onBeforeMount(async () => {
    try {
        await http.get(`api/teacher/quizzes/${route.params.quizId}`);
    } catch (error) {
        if (error.response.status == 404) {
            router.push({ name: 'error.404' });
        } else {
            console.log('Something went wrong!');
        }
    }
});
</script>

<template>
    <BasePageHeading title="Create question">
        <template #extra>
            <RouterLink :to="{ name: 'teacher.quizzes.edit', params: { id: route.params.quizId } }"
                class="btn btn-alt-secondary" v-click-ripple>
                <i class="fa fa-undo opacity-50 me-1"></i>
                Back
            </RouterLink>
        </template>
    </BasePageHeading>

    <div class="content">
        <BaseBlock content-full>
            <form @submit.prevent="save">
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="question" name="question" placeholder="question"
                        :class="{ 'is-invalid': v$.question.$errors.length }" v-model="state.question"
                        @blur="v$.question.$touch" />
                    <label class="first-capitalize" for="question">question</label>
                    <div v-if="v$.question.$errors.length" class="invalid-feedback animated fadeIn">
                        {{ v$.question.$errors[0].$message }}
                    </div>
                </div>
                <div class="form-floating mb-4">
                    <select class="form-select" id="type" name="type" aria-label="type"
                        :class="{ 'is-invalid': v$.type.$errors.length }" v-model="state.type">
                        <option value="1">Radio</option>
                        <option value="2">Checkbox</option>
                    </select>
                    <label class="first-capitalize" for="type">type</label>
                    <div v-if="v$.type.$errors.length" class="invalid-feedback animated fadeIn">
                        {{ v$.type.$errors[0].$message }}
                    </div>
                </div>
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="time" name="time" placeholder="time"
                        :class="{ 'is-invalid': v$.time.$errors.length }" v-model="state.time" @blur="v$.time.$touch" />
                    <label class="first-capitalize" for="time">time</label>
                    <div v-if="v$.time.$errors.length" class="invalid-feedback animated fadeIn">
                        {{ v$.time.$errors[0].$message }}
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-alt-primary">
                        <i class="fa fa-fw fa-save me-1 opacity-50"></i> Save
                    </button>
                </div>
            </form>
        </BaseBlock>
    </div>
</template>
