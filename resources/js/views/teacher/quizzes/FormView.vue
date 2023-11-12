<script setup>
import { reactive, computed } from "vue";
import { useRouter } from "vue-router";

import useVuelidate from "@vuelidate/core";
import { required, minLength } from "@vuelidate/validators";

const router = useRouter();

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    quiz: {
        type: Object,
        default: null,
    },
});

const state = reactive({
    name: props.quiz?.name ?? null,
    active: props.quiz?.active ?? false,
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

const save = async () => {
    const result = await v$.value.$validate();

    if (!result) {
        return;
    }

    try {
        if (props.quiz) {
            await http.put(`api/teacher/quizzes/${props.quiz.id}`, state);
        } else {
            await http.post("api/teacher/quizzes", state);
        }

        router.push({ name: `teacher.quizzes.index` });
    } catch (error) {
        console.log(error.response.data.message);
    }
};
</script>

<template>
    <BasePageHeading :title="title">
        <template #extra>
            <RouterLink :to="{ name: 'teacher.quizzes.index' }" class="btn btn-alt-secondary" v-click-ripple>
                <i class="fa fa-fw fa-undo opacity-50 me-1"></i>
                Back
            </RouterLink>
        </template>
    </BasePageHeading>

    <div class="content">
        <BaseBlock content-full>
            <form @submit.prevent="save">
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
                    <button type="submit" class="btn btn-lg btn-alt-primary">
                        <i class="fa fa-fw fa-save me-1 opacity-50"></i> Save
                    </button>
                </div>
            </form>
        </BaseBlock>
    </div>
</template>
