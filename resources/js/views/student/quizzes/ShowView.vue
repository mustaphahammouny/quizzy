<script setup>
import { onBeforeMount, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();

const quiz = ref(null);

onBeforeMount(async () => {
    try {
        const response = await http.get(`api/student/quizzes/${route.params.id}`);

        quiz.value = response.data.data;
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
    <template v-if="quiz">
        <BasePageHeading title="Quiz" :subtitle="quiz.name">
            <template #extra>
                <RouterLink :to="{ name: 'student.quizzes.index' }" class="btn btn-alt-secondary" v-click-ripple>
                    <i class="fa fa-fw fa-undo opacity-50 me-1"></i>
                    Back
                </RouterLink>
            </template>
        </BasePageHeading>

        <div class="content">
            <div class="row row-cols-1 row-cols-md-2">
                <div class="col">
                    <BaseBlock title="About this quiz" content-full>
                        <table class="table table-striped table-borderless fs-sm">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fa fa-fw fa-question me-1"></i>
                                        {{ quiz.name }}
                                    </td>
                                </tr>
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
                                        <i class="fa fa-fw fa-heart me-1"></i>
                                        {{ quiz.favorites_count }} Favorites
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-fw fa-calendar me-1"></i>
                                        {{ quiz.created_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-fw fa-tags me-1"></i>
                                        <span class="badge bg-primary">HTML</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button class="btn btn-alt-primary w-100">start</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </BaseBlock>
                </div>
                <div class="col">
                    <BaseBlock title="About the teacher" content-full>
                        <div class="text-center m">
                            <div class="push">
                                <img class="img-avatar" src="assets/media/avatars/avatar16.jpg" alt="">
                            </div>
                            <div class="fw-semibold mb-1">{{ quiz.teacher.full_name }}</div>
                            <div class="fs-sm text-muted">Email: {{ quiz.teacher.email }}</div>
                            <div class="fs-sm text-muted">Since: {{ quiz.teacher.created_at }}</div>
                        </div>
                    </BaseBlock>
                </div>
            </div>
        </div>
    </template>
</template>
