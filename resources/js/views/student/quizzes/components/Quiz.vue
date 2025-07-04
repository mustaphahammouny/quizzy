<script setup>
import { ref } from "vue";
import alert from "@/support/alert";

const props = defineProps({
    quiz: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["favorite-toggled"]);

const quiz = ref(props.quiz);

const favoriteQuiz = async () => {
    try {
        const response = await http.get(
            `api/student/quizzes/${quiz.value.id}/favorite`
        );

        quiz.value.favorite = response.data.data.favorite;

        let title = "Quiz added to favorites!";
        if (!quiz.value.favorite) {
            title = "Quiz removed from favorites!";
        }

        alert.success(title);

        emit("favorite-toggled");
    } catch (error) {
        console.log(error);
        // console.log(error.response.data.message);
    }
};
</script>

<template>
    <div class="col">
        <BaseBlock
            rounded
            class="overflow-hidden d-flex flex-column h-100 mb-0"
        >
            <template #content>
                <div
                    class="block-content ribbon p-0"
                    :class="`ribbon-${quiz.level.color}`"
                >
                    <div class="ribbon-box ribbon-sm">
                        {{ quiz.level.name }}
                    </div>
                    <div class="options-container fx-item-zoom-in">
                        <img
                            class="img-fluid img-height w-100 options-item object-fit-cover"
                            :src="quiz.image"
                            :alt="quiz.name"
                        />
                        <div class="options-overlay bg-black-75">
                            <div class="options-overlay-content">
                                <button
                                    v-on:click="($event) => favoriteQuiz()"
                                    class="btn btn-sm btn-alt-danger rounded-pill"
                                >
                                    <i
                                        class="fa-fw fa-heart"
                                        :class="{
                                            far: !quiz.favorite,
                                            fa: quiz.favorite,
                                        }"
                                    ></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block-content flex-grow-1">
                    <RouterLink
                        :to="{
                            name: 'student.quizzes.show',
                            params: { id: quiz.id },
                        }"
                        class="fw-bold text-primary-emphasis"
                    >
                        <h4 class="text-center">{{ quiz.name }}</h4>
                    </RouterLink>

                    <ul class="nav nav-pills flex-column mt-4">
                        <li
                            class="nav-item d-flex justify-content-between align-items-center mb-1"
                        >
                            <span class="text-secondary fw-bold">Teacher:</span>
                            <span class="text-secondary fw-semibold">{{
                                quiz.teacher.full_name
                            }}</span>
                        </li>
                        <li
                            class="nav-item d-flex justify-content-between align-items-center mb-1"
                        >
                            <span class="text-secondary fw-bold">Total:</span>
                            <span class="text-secondary fw-semibold">
                                {{ quiz.questions_count ?? 0 }} questions
                            </span>
                        </li>
                        <li
                            class="nav-item d-flex justify-content-between align-items-center mb-1"
                        >
                            <span class="text-secondary fw-bold">Time:</span>
                            <span class="text-secondary fw-semibold">
                                {{ quiz.questions_sum_time ?? 0 }}
                                Seconds
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="block-content block-content-full">
                    <RouterLink
                        :to="{
                            name: 'student.quizzes.show',
                            params: { id: quiz.id },
                        }"
                        class="btn btn-sm btn-alt-primary w-100"
                    >
                        <i class="fa fa-fw fa-eye me-1"></i>
                        View
                    </RouterLink>
                </div>
            </template>
        </BaseBlock>
    </div>
</template>
