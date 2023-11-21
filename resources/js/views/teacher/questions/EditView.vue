<script setup>
import { reactive, computed, onBeforeMount, ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";

import {
    Dataset,
    DatasetItem,
    DatasetInfo,
    DatasetPager,
    DatasetSearch,
    DatasetShow,
} from "vue-dataset";

import { useNotificationStore } from "@/stores/notification.store";

import alert from '@/support/alert';
import dataset from '@/support/dataset';

import FormView from "@/views/teacher/questions/FormView.vue";

const router = useRouter();
const route = useRoute();
const notification = useNotificationStore();

const cols = reactive([
    {
        name: "answer",
        field: "answer",
        sort: "",
        with: "100%",
    },
]);

const quizId = ref(null);
const question = ref(null);
const answers = ref([]);

const sortBy = computed(() => {
    return dataset.sortBy(cols);
});

const deleteAnswer = (answer) => {
    alert.confirm({
        text: '',
        callback: async () => {
            try {
                await http.delete(`api/teacher/answers/${answer.id}`);

                answers.value = answers.value.filter((item) => item.id !== answer.id);

                alert.success('Answer deleted successfully!');
            } catch (error) {
                console.log(error.response.message ?? error.response.data.message);
            }
        },
    });
};

onBeforeMount(async () => {
    notification.show();

    try {
        const response = await http.get(`api/teacher/questions/${route.params.id}`);

        quizId.value = response.data.data.quiz_id;
        question.value = response.data.data;
        answers.value = response.data.data.answers;
    } catch (error) {
        if (error.response.status == 404) {
            router.push({ name: 'error.404' });
        } else {
            console.log('Something went wrong!');
        }
    }
});

onMounted(() => {
    dataset.css();
});
</script>

<template>
    <FormView v-if="question" :quiz-id="quizId" :question="question" title="Edit question" />

    <div class="content pt-0">
        <BaseBlock title="Answers" content-full>
            <template #options>
                <RouterLink :to="{ name: 'teacher.answers.create', params: { questionId: route.params.id } }"
                    class="btn btn-alt-primary" v-click-ripple>
                    <i class="fa fa-fw fa-plus opacity-50 me-1"></i>
                    Add Answer
                </RouterLink>
            </template>
            <Dataset v-slot="{ ds }" :ds-data="answers" :ds-sortby="sortBy" :ds-search-in="['name', 'created_at']">
                <div class="row" :data-page-count="ds.dsPagecount">
                    <div id="datasetLength" class="col-md-8 py-2">
                        <DatasetShow />
                    </div>
                    <div class="col-md-4 py-2">
                        <DatasetSearch ds-search-placeholder="Search..." />
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter mb-0">
                                <thead>
                                    <tr>
                                        <th v-for="(th, index) in cols" :key="th.field" :class="['sort', th.sort]"
                                            @click="dataset.onSort($event, cols, index)" :width="th.with ?? 'auto'">
                                            <span class="float-start">{{ th.name }}</span>
                                            <i class="gg-select float-end"></i>
                                        </th>
                                        <th class="text-center">Correct</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <DatasetItem tag="tbody" class="fs-sm">
                                    <template #default="{ row, rowIndex }">
                                        <tr>
                                            <td>{{ row.answer }}</td>
                                            <td class="text-center">
                                                <span class="badge"
                                                    :class="{ 'text-bg-success': row.correct, 'text-bg-danger': !row.correct }">
                                                    {{ row.correct ? 'Correct' : 'Incorrect' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <RouterLink
                                                        :to="{ name: 'teacher.answers.edit', params: { id: row.id } }"
                                                        class="btn btn-sm btn-alt-success">
                                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                                    </RouterLink>
                                                    <button type="button" v-on:click="$event => deleteAnswer(row)"
                                                        class="btn btn-sm btn-alt-danger">
                                                        <i class="fa fa-fw fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </DatasetItem>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-md-row flex-column justify-content-between align-items-center">
                    <DatasetInfo class="py-3 fs-sm" />
                    <DatasetPager class="flex-wrap py-3 fs-sm" />
                </div>
            </Dataset>
        </BaseBlock>
    </div>
</template>

<style lang="scss" scoped>
@import "@/assets/scss/custom/dataset";
</style>
