<script setup>
import { ref, onBeforeMount } from "vue";

import Pagination from '@/views/student/quizzes/components/Pagination.vue';
import Empty from '@/components/Empty.vue';

const passedQuizzes = ref([]);
const meta = ref({});
const page = ref(1);

const paginate = async (to) => {
    page.value = to;
    await getQuizzes();
};

const getQuizzes = async () => {
    try {
        const response = await http.get("api/student/passed-quizzes", { page: page.value });

        passedQuizzes.value = response.data.data;
        meta.value = response.data.meta;
    } catch (error) {
        console.log(error.response.data.message);
    }
};

onBeforeMount(async () => {
    await getQuizzes();
});
</script>

<template>
    <BasePageHeading title="Passed quizzes" />

    <div class="content content-boxed py-0">
        <template v-if="passedQuizzes.length">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 items-push py-4">
                <div v-for="passedQuiz in passedQuizzes" :key="passedQuiz.id" class="col">
                    <BaseBlock content-full :ribbon="passedQuiz.quiz.level.name" :ribbonVariant="passedQuiz.quiz.level.color">
                        <h4 class="h5 text-center mb-2">
                            {{ passedQuiz.quiz.name }}
                        </h4>
                        <table class="table table-borderless fs-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fa fa-fw fa-user me-1"></i>
                                        {{ passedQuiz.quiz.teacher.full_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-fw fa-edit me-1"></i>
                                        {{ passedQuiz.items_count }} questions
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-fw fa-clock me-1"></i>
                                        {{ passedQuiz.items_sum_time }} Seconds
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-fw fa-check me-1"></i>
                                        {{ passedQuiz.correct_count }} / {{ passedQuiz.items_count }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="block-content block-content-full">
                            <div class="d-flex justify-content-between">
                                <RouterLink :to="{ name: 'student.quizzes.start', params: { id: passedQuiz.quiz.id } }"
                                    class="btn btn-sm btn-alt-primary w-100">
                                    <i class="fa fa-fw fa-undo"></i>
                                    Restart
                                </RouterLink>
                            </div>
                        </div>
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
