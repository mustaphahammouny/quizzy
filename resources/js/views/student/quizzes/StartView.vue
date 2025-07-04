<script setup>
import { onBeforeMount, ref, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import Question from "@/views/student/quizzes/components/Question.vue";
import Result from "./components/Result.vue";

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
        console.log("Something went wrong!");
    }
};

onBeforeMount(async () => {
    try {
        const response = await http.get(
            `api/student/quizzes/${route.params.id}/questions`
        );

        quiz.value = response.data.data;
    } catch (error) {
        if (error.response.status == 404) {
            router.push({ name: "error.404" });
        } else {
            console.log("Something went wrong!");
        }
    }
});
</script>

<template>
    <template v-if="quiz">
        <BasePageHeading title="Quiz" :subtitle="quiz.name">
            <template #extra>
                <RouterLink
                    :to="{ name: 'student.quizzes.index' }"
                    class="btn btn-alt-secondary"
                    v-click-ripple
                >
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
                            <Question
                                :question="currentQuestion"
                                :callback="nextQuestion"
                                :key="currentQuestion.id"
                            />
                        </template>
                        <template v-else>
                            <Result :passed-quiz="passedQuiz" />
                        </template>
                    </div>
                </div>
            </BaseBlock>
        </div>
    </template>
</template>
