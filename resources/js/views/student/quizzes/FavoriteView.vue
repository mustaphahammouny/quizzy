<script setup>
import { ref, onBeforeMount } from "vue";

import alert from '@/support/alert';

import Quiz from '@/views/student/quizzes/components/Quiz.vue';
import Pagination from '@/views/student/quizzes/components/Pagination.vue';
import Empty from '@/components/Empty.vue';

const quizzes = ref([]);
const meta = ref({});
const page = ref(1);

const paginate = async (to) => {
    page.value = to;
    await getQuizzes();
};

const getQuizzes = async () => {
    try {
        const response = await http.get("api/student/quizzes/favorite", { page: page.value });

        quizzes.value = response.data.data;
        meta.value = response.data.meta;
    } catch (error) {
        console.log(error.response.data.message);
    }
};

const success = (data) => {
    quizzes.value = quizzes.value.filter((item) => item.id != data.id);

    alert.success('Quiz deleted successfully!');
}

onBeforeMount(async () => {
    await getQuizzes();
});
</script>

<template>
    <BasePageHeading title="Favorite quizzes" />

    <div class="content content-boxed py-0">
        <template v-if="quizzes.length">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 items-push py-4">

                <Quiz v-for="quiz in quizzes" :quiz="quiz" :key="quiz.id" :success="success" />
            </div>

            <Pagination :meta="meta" :paginate="paginate" />
        </template>
        <template v-else>
            <Empty />
        </template>
    </div>
</template>
