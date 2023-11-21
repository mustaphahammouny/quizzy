<script setup>
import { reactive, computed } from "vue";
import { useRouter } from "vue-router";

import useVuelidate from "@vuelidate/core";
import { required, minLength, numeric } from "@vuelidate/validators";

const router = useRouter();

const props = defineProps({
    quizId: {
        type: [Number, String],
        required: true,
    },
    title: {
        type: String,
        default: '',
    },
    question: {
        type: Object,
        default: null,
    },
});

const state = reactive({
    quiz_id: props.quizId,
    question: props.question?.question ?? null,
    type: props.question?.type.value ?? null,
    time: props.question?.time ?? null,
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
        if (props.question) {
            await http.put(`api/teacher/questions/${props.question.id}`, state);
        } else {
            await http.post("api/teacher/questions", state);
        }

        router.push({ name: `teacher.quizzes.edit`, params: { id: props.quizId } });
    } catch (error) {
        console.log(error.response.data.message);
    }
};
</script>

<template>
    <BasePageHeading :title="title">
        <template #extra>
            <RouterLink :to="{ name: 'teacher.quizzes.edit', params: { id: quizId } }" class="btn btn-alt-secondary"
                v-click-ripple>
                <i class="fa fa-fw fa-undo opacity-50 me-1"></i>
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
                <div class="mb-4">
                    <VSelect placeholder="Type" :options="[{ label: 'Radio', value: 1 }, { label: 'Checkbox', value: 2 }]"
                        :reduce="type => type.value" :class="{ 'is-invalid': v$.type.$errors.length }" v-model="state.type">
                    </VSelect>
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
