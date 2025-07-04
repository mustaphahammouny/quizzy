<script setup>
const props = defineProps({
    passedQuiz: {
        type: Object,
        required: true,
    },
});
</script>

<template>
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
            <p class="badge bg-primary-light fs-4">
                {{ passedQuiz.items_count }}
            </p>
            <div class="fw-semibold text-dark mb-1">Questions</div>
        </div>
        <div class="col-6 col-md-4">
            <p class="badge bg-primary-light fs-4">
                {{ passedQuiz.correct_count }}
            </p>
            <div class="fw-semibold text-dark mb-1">Correct questions</div>
        </div>
        <div class="col-12 col-md-4">
            <p class="badge bg-primary-light fs-4">
                {{ passedQuiz.correct_count }} /
                {{ passedQuiz.items_count }}
            </p>
            <div class="fw-semibold text-dark mb-1">Score</div>
        </div>
    </div>
    <div class="row items-push text-center mt-4">
        <div class="col">
            <RouterLink
                :to="{ name: 'student.quizzes.passed' }"
                class="btn btn-alt-primary w-100"
            >
                Passed quizzes
            </RouterLink>
        </div>
    </div>
</template>
