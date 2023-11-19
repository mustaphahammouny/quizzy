<script setup>
import { onMounted, reactive, ref } from 'vue';

import Answers from '@/views/student/quizzes/components/Answers.vue';

const props = defineProps({
    question: {
        type: Object,
        required: true,
    },
    callback: {
        type: Function,
        required: true,
    },
});

const timer = ref(null);

const state = reactive({
    answers: [],
});

const startTimer = () => {
    timer.value = setTimeout(() => {
        submit();
    }, props.question.time * 1000);
};

const submit = () => {
    clearTimeout(timer.value);

    props.callback({
        [props.question.id]: state.answers,
    });
};

onMounted(() => {
    startTimer();
});
</script>

<template>
    <form @submit.prevent="submit">
        <h1 class="content-heading border-bottom mb-4 pb-2">{{ question.question }}</h1>

        <Answers :answers="question.answers" :type="question.type.name" v-model="state.answers" />

        <div class="text-end">
            <button type="submit" class="btn btn-dark">Next</button>
        </div>
    </form>
</template>