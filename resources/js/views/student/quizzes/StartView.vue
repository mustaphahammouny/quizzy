<script setup>
import { onBeforeMount, ref, computed } from "vue";
import { useRoute, useRouter } from "vue-router";

import Question from '@/views/student/quizzes/components/Question.vue';

const router = useRouter();
const route = useRoute();

const quiz = ref(null);
const currentQuestionIndex = ref(0);
const answers = ref({});
const passedQuiz = ref(null);

const currentQuestion = computed(() => {
    return quiz.value.questions[currentQuestionIndex.value];
});

const nextQuestion = async (answer) => {
    answers.value = {
        ...answers.value,
        ...answer,
    };

    if (currentQuestionIndex.value < quiz.value.questions.length - 1) {
        currentQuestionIndex.value++;
        return;
    }

    await finishQuiz();
};

const finishQuiz = async () => {
    try {
        const response = await http.post(`api/student/passed-quizzes`, {
            quiz_id: quiz.value.id,
            answers: answers.value,
        });

        passedQuiz.value = response.data.data;
    } catch (error) {
        console.log('Something went wrong!');
    }
};

onBeforeMount(async () => {
    try {
        const response = await http.get(`api/student/quizzes/${route.params.id}/questions`);

        quiz.value = response.data.data;
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
    <template v-if="quiz">
        <BasePageHeading title="Quiz" :subtitle="quiz.name">
            <template #extra>
                <RouterLink :to="{ name: 'student.quizzes.index' }" class="btn btn-alt-secondary" v-click-ripple>
                    <i class="fa fa-fw fa-undo opacity-50 me-1"></i>
                    Back
                </RouterLink>
            </template>
        </BasePageHeading>

        <div class="content">
            <BaseBlock content-full>
                <div class="row">
                    <div class="col-12 col-md-8 col-xl-6 mx-auto">
                        <template v-if="!passedQuiz">
                            <Question :question="currentQuestion" :callback="nextQuestion" :key="currentQuestion.id" />
                        </template>
                        <template v-else>
                            <div class="text-center py-4">
                                <h1 class="fs-lg mb-0">
                                    {{ passedQuiz.quiz.name }}
                                </h1>
                                <p class="fs-sm fw-medium text-muted">
                                    {{ passedQuiz.quiz.teacher.full_name }}
                                </p>
                            </div>
                            <div class="row items-push text-center">
                                <div class="col-6 col-md-4">
                                    <p class="badge bg-primary-light fs-4">{{ passedQuiz.items_count }}</p>
                                    <div class="fw-semibold text-dark mb-1">Questions</div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <p class="badge bg-primary-light fs-4">{{ passedQuiz.correct_count }}</p>
                                    <div class="fw-semibold text-dark mb-1">Correct questions</div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <p class="badge bg-primary-light fs-4">{{ passedQuiz.correct_count }} / {{
                                        passedQuiz.items_count }}</p>
                                    <div class="fw-semibold text-dark mb-1">Score</div>
                                </div>
                            </div>
                            <div class="row items-push text-center mt-4">
                                <div class="col">
                                    <RouterLink :to="{ name: 'student.quizzes.passed' }"
                                        class="btn btn-alt-primary w-100">Passed quizzes</RouterLink>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </BaseBlock>
        </div>
    </template>
</template>
