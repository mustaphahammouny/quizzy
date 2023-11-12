<script setup>
import { onBeforeMount } from "vue";
import { useRouter, useRoute } from "vue-router";

import FormView from "@/views/teacher/answers/FormView.vue";

const router = useRouter();
const route = useRoute();

onBeforeMount(async () => {
    try {
        await http.get(`api/teacher/questions/${route.params.questionId}`);
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
    <FormView :question-id="route.params.questionId" title="Create answer" />
</template>
