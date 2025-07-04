<script setup>
import { ref, onBeforeMount } from "vue";
import Pagination from "@/views/student/quizzes/components/Pagination.vue";
import Empty from "@/components/Empty.vue";

const passedQuizzes = ref([]);
const meta = ref({});
const page = ref(1);

const paginate = async (to) => {
    page.value = to;
    await getPassedQuizzes();
};

const getPassedQuizzes = async () => {
    try {
        const response = await http.get("api/student/passed-quizzes", {
            page: page.value,
        });

        passedQuizzes.value = response.data.data;
        meta.value = response.data.meta;
    } catch (error) {
        console.log(error.response.data.message);
    }
};

onBeforeMount(getPassedQuizzes);
</script>

<template>
    <BasePageHeading title="Passed Quizzes" />

    <div class="content content-boxed py-0">
        <template v-if="passedQuizzes.length">
            <div
                class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 items-push py-4"
            >
                <div
                    v-for="passedQuiz in passedQuizzes"
                    :quiz="passedQuiz"
                    :key="passedQuiz.id"
                    class="col"
                >
                    <BaseBlock
                        rounded
                        class="overflow-hidden d-flex flex-column h-100 mb-0"
                    >
                        <template #content>
                            <div
                                class="block-content ribbon p-0"
                                :class="`ribbon-${passedQuiz.quiz.level.color}`"
                            >
                                <div class="ribbon-box ribbon-sm">
                                    {{ passedQuiz.quiz.level.name }}
                                </div>
                                <div class="options-container fx-item-zoom-in">
                                    <img
                                        class="img-fluid img-height w-100 options-item object-fit-cover"
                                        :src="passedQuiz.quiz.image"
                                        :alt="passedQuiz.quiz.name"
                                    />
                                </div>
                            </div>

                            <div class="block-content flex-grow-1">
                                <RouterLink
                                    :to="{
                                        name: 'student.quizzes.show',
                                        params: { id: passedQuiz.id },
                                    }"
                                    class="fw-bold text-primary-emphasis"
                                >
                                    <h4 class="text-center">
                                        {{ passedQuiz.quiz.name }}
                                    </h4>
                                </RouterLink>

                                <ul class="nav nav-pills flex-column mt-4">
                                    <li
                                        class="nav-item d-flex justify-content-between align-items-center mb-1"
                                    >
                                        <span class="text-secondary fw-bold"
                                            >Teacher:</span
                                        >
                                        <span
                                            class="text-secondary fw-semibold"
                                            >{{
                                                passedQuiz.quiz.teacher
                                                    .full_name
                                            }}</span
                                        >
                                    </li>
                                    <li
                                        class="nav-item d-flex justify-content-between align-items-center mb-1"
                                    >
                                        <span class="text-secondary fw-bold"
                                            >Total:</span
                                        >
                                        <span
                                            class="text-secondary fw-semibold"
                                        >
                                            {{ passedQuiz.items_count }}
                                            questions
                                        </span>
                                    </li>
                                    <li
                                        class="nav-item d-flex justify-content-between align-items-center mb-1"
                                    >
                                        <span class="text-secondary fw-bold"
                                            >Time:</span
                                        >
                                        <span
                                            class="text-secondary fw-semibold"
                                        >
                                            {{ passedQuiz.items_sum_time }}
                                            Seconds
                                        </span>
                                    </li>
                                    <li
                                        class="nav-item d-flex justify-content-between align-items-center mb-1"
                                    >
                                        <span class="text-secondary fw-bold"
                                            >Score:</span
                                        >
                                        <span
                                            class="text-secondary fw-semibold"
                                        >
                                            {{ passedQuiz.correct_count }} /
                                            {{ passedQuiz.items_count }}
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <div class="block-content block-content-full">
                                <RouterLink
                                    :to="{
                                        name: 'student.quizzes.start',
                                        params: { id: passedQuiz.quiz.id },
                                    }"
                                    class="btn btn-sm btn-alt-primary w-100"
                                >
                                    <i class="fa fa-fw fa-undo me-1"></i>
                                    Restart
                                </RouterLink>
                            </div>
                        </template>
                    </BaseBlock>
                </div>
            </div>

            <Pagination :meta="meta" :paginate="paginate" />
        </template>
        <template v-else>
            <Empty />
        </template>
    </div>
</template>
