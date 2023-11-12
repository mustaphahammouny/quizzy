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

import alert from '@/support/alert';
import dataset from '@/support/dataset';

import FormView from "@/views/teacher/quizzes/FormView.vue";

const router = useRouter();
const route = useRoute();

const cols = reactive([
    {
        name: "question",
        field: "question",
        sort: "",
        with: "60%",
    },
    {
        name: "type",
        field: "type",
        sort: "",
        with: "20%",
    },
    {
        name: "time (min)",
        field: "time",
        sort: "",
        with: "20%",
    },
]);

const quiz = ref(null);
const questions = ref([]);

const sortBy = computed(() => {
    return dataset.sortBy(cols);
});

const deleteQuestion = (question) => {
    alert.confirm({
        text: 'All the answers related to this question will be deleted!',
        callback: async () => {
            try {
                await http.delete(`api/teacher/questions/${question.id}`);

                questions.value = questions.value.filter((item) => item.id !== question.id);
            } catch (error) {
                console.log(error.response.message ?? error.response.data.message);
            }
        },
    });
};

onBeforeMount(async () => {
    try {
        const response = await http.get(`api/teacher/quizzes/${route.params.id}`);

        quiz.value = response.data.data;
        questions.value = response.data.data.questions;
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
    <FormView v-if="quiz" :quiz="quiz" title="Edit quiz" />

    <div class="content pt-0">
        <BaseBlock title="Questions" content-full>
            <template #options>
                <RouterLink :to="{ name: 'teacher.questions.create', params: { quizId: route.params.id } }"
                    class="btn btn-alt-primary" v-click-ripple>
                    <i class="fa fa-fw fa-plus opacity-50 me-1"></i>
                    Add Question
                </RouterLink>
            </template>
            <Dataset v-slot="{ ds }" :ds-data="questions" :ds-sortby="sortBy" :ds-search-in="['name', 'created_at']">
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
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <DatasetItem tag="tbody" class="fs-sm">
                                    <template #default="{ row, rowIndex }">
                                        <tr>
                                            <td>{{ row.question }}</td>
                                            <td>{{ row.type.name }}</td>
                                            <td>{{ row.time }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <RouterLink
                                                        :to="{ name: 'teacher.questions.edit', params: { id: row.id } }"
                                                        class="btn btn-sm btn-alt-success">
                                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                                    </RouterLink>
                                                    <button type="button" v-on:click="$event => deleteQuestion(row)"
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
