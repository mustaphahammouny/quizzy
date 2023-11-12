<script setup>
import { onBeforeMount, ref } from "vue";
import { useRouter, useRoute } from "vue-router";

import FormView from "@/views/teacher/answers/FormView.vue";

const router = useRouter();
const route = useRoute();

const questionId = ref(null);
const answer = ref(null);

onBeforeMount(async () => {
    try {
        const response = await http.get(`api/teacher/answers/${route.params.id}`);

        questionId.value = response.data.data.question_id;
        answer.value = response.data.data;
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
    <FormView v-if="answer" :question-id="questionId" :answer="answer" title="Edit answer" />
</template>
