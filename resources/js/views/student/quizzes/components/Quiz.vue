<script setup>
import { ref } from 'vue';

const props = defineProps({
    quiz: {
        type: Object,
        required: true,
    },
    success: {
        type: Function,
        required: true,
    },
});

const quiz = ref(props.quiz);

const favoriteQuiz = async () => {
    try {
        const response = await http.get(`api/student/quizzes/${quiz.value.id}/favorite`);

        props.success(response.data.data);
    } catch (error) {
        console.log(error.response.data.message);
    }
};
</script>

<template>
    <div class="col">
        <BaseBlock>
            <h4 class="h5 text-center mb-2">
                {{ quiz.name }}
            </h4>
            <table class="table table-borderless fs-sm mb-0">
                <tbody>
                    <tr>
                        <td>
                            <i class="fa fa-fw fa-user me-1"></i>
                            {{ quiz.teacher.full_name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <i class="fa fa-fw fa-edit me-1"></i>
                            {{ quiz.questions_count }} questions
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <i class="fa fa-fw fa-clock me-1"></i>
                            {{ quiz.questions_sum_time }} Seconds
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <i class="fa fa-fw fa-calendar me-1"></i>
                            {{ quiz.created_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="block-content block-content-full">
                <div class="d-flex justify-content-between">
                    <RouterLink :to="{ name: 'student.quizzes.show', params: { id: quiz.id } }"
                        class="btn btn-sm rounded-pill btn-alt-primary">
                        <i class="fa fa-fw fa-eye"></i>
                    </RouterLink>
                    <button v-on:click="$event => favoriteQuiz()" class="btn btn-sm rounded-pill" :class="{
                        'btn-outline-secondary': !quiz.favorite,
                        'btn-outline-danger': quiz.favorite,
                    }">
                        <i class="fa fa-fw fa-heart"></i>
                    </button>
                </div>
            </div>
        </BaseBlock>
    </div>
</template>