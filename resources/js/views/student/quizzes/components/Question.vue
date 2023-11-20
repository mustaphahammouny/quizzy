<script setup>
import { computed, onMounted, reactive, ref } from 'vue';

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
const time = ref(0);

const progress = computed(() => {
    const timeLeft = props.question.time - Math.floor(time.value / 10)

    return {
        percent: Math.floor(time.value * 10 / props.question.time),
        timeLeft: timeLeft > 0 ? timeLeft : 0,
    };
});

const state = reactive({
    answers: [],
});

const startTimer = () => {
    timer.value = setInterval(() => {
        if ((time.value / 10) >= props.question.time + 1) {
            submit();
        } else {
            time.value++;
        }
    }, 100);
};

const submit = () => {
    clearInterval(timer.value);

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
        <span>{{ progress.timeLeft }} Second</span>
        <div class="progress push" :style="{ height: '10px' }" role="progressbar" :aria-valuenow="progress.percent"
            aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" :style="{ width: `${progress.percent}%` }">
            </div>
        </div>

        <h1 class="content-heading border-bottom mb-4 pb-2">{{ question.question }}</h1>

        <Answers :answers="question.answers" :type="question.type.name" v-model="state.answers" />

        <div class="text-end">
            <button type="submit" class="btn btn-dark">Next</button>
        </div>
    </form>
</template>