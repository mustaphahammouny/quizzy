<script setup>
import { reactive, computed } from "vue";
import { useRouter } from "vue-router";

import useVuelidate from "@vuelidate/core";
import { required, minLength } from "@vuelidate/validators";

import { useNotificationStore } from "@/stores/notification.store";

const router = useRouter();
const notification = useNotificationStore();

const props = defineProps({
    questionId: {
        type: [Number, String],
        required: true,
    },
    title: {
        type: String,
        default: '',
    },
    answer: {
        type: Object,
        default: null,
    },
});

const state = reactive({
    question_id: props.questionId,
    answer: props.answer?.answer ?? null,
    correct: props.answer?.correct ?? false,
});

const rules = computed(() => {
    return {
        answer: {
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
        let title = 'Answer added successfully!';

        if (props.answer) {
            await http.put(`api/teacher/answers/${props.answer.id}`, state);

            title = 'Answer updated successfully!';
        } else {
            await http.post("api/teacher/answers", state);
        }

        notification.setMessage({ title: title, icon: 'success' });

        router.push({ name: `teacher.questions.edit`, params: { id: props.questionId } });
    } catch (error) {
        console.log(error.response.data.message);
    }
};
</script>

<template>
    <BasePageHeading :title="title">
        <template #extra>
            <RouterLink :to="{ name: 'teacher.questions.edit', params: { id: questionId } }" class="btn btn-alt-secondary"
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
                    <input type="text" class="form-control" id="answer" name="answer" placeholder="answer"
                        :class="{ 'is-invalid': v$.answer.$errors.length }" v-model="state.answer"
                        @blur="v$.answer.$touch" />
                    <label class="first-capitalize" for="answer">answer</label>
                    <div v-if="v$.answer.$errors.length" class="invalid-feedback animated fadeIn">
                        {{ v$.answer.$errors[0].$message }}
                    </div>
                </div>
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="correct" name="correct" v-model="state.correct">
                        <label class="form-check-label" for="correct">Correct</label>
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
