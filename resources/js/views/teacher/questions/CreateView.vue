<script setup>
import { onBeforeMount } from "vue";
import { useRouter, useRoute } from "vue-router";

import FormView from "@/views/teacher/questions/FormView.vue";

const router = useRouter();
const route = useRoute();

onBeforeMount(async () => {
    try {
        await http.get(`api/teacher/quizzes/${route.params.quizId}`);
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
    <FormView :quiz-id="route.params.quizId" title="Create question" />
</template>
