<script setup>
import { onBeforeMount, ref } from 'vue';

const menu = [
    {
        name: "quizzes",
        to: "teacher.quizzes.index",
        icon: "fa fa-list",
        key: "quizzes_count",
    },
];

const counts = ref({});

onBeforeMount(async () => {
    try {
        const response = await http.get("api/teacher/counts");

        counts.value = response.data.data;
    } catch (error) {
        console.log(error.response.data.message);
    }
});
</script>

<template>
    <BasePageHeading title="Dashboard" />

    <div class="content">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 items-push">
            <div class="col" v-for="(node, index) in menu" :key="`node-${index}`">
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{ counts[node.key] }}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">{{ node.name }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i :class="`fs-3 text-primary ${node.icon}`"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <RouterLink :to="{ name: node.to }"
                            class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between">
                            <span>View all</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </RouterLink>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
