<script setup>
import { ref, reactive, onBeforeMount, onMounted, computed } from "vue";

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

const notification = useNotificationStore();

const cols = reactive([
    {
        name: "name",
        field: "name",
        sort: "",
        with: "30%",
    },
    {
        name: "created at",
        field: "created_at",
        sort: "",
        with: "30%",
    },
]);

const quizzes = ref([]);

const sortBy = computed(() => {
    return dataset.sortBy(cols);
});

const deleteQuiz = (quiz) => {
    alert.confirm({
        text: 'All the questions related to this quiz will be deleted!',
        callback: async () => {
            try {
                await http.delete(`api/teacher/quizzes/${quiz.id}`);

                quizzes.value = quizzes.value.filter((item) => item.id !== quiz.id);

                alert.success('Quiz deleted successfully!');
            } catch (error) {
                console.log(error.response.message ?? error.response.data.message);
            }
        },
    });
};

onBeforeMount(async () => {
    notification.show();

    try {
        const response = await http.get("api/teacher/quizzes");

        quizzes.value = response.data.data;
    } catch (error) {
        console.log(error.response.data.message);
    }
});

onMounted(() => {
    dataset.css();
});
</script>

<template>
    <BasePageHeading title="Quizzes">
        <template #extra>
            <RouterLink :to="{ name: 'teacher.quizzes.create' }" class="btn btn-alt-primary" v-click-ripple>
                <i class="fa fa-plus opacity-50 me-1"></i>
                Add Quiz
            </RouterLink>
        </template>
    </BasePageHeading>

    <div class="content">
        <BaseBlock content-full>
            <Dataset v-slot="{ ds }" :ds-data="quizzes" :ds-sortby="sortBy" :ds-search-in="['name', 'created_at']">
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
                                        <th class="text-center">Level</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <DatasetItem tag="tbody" class="fs-sm">
                                    <template #default="{ row, rowIndex }">
                                        <tr>
                                            <td>{{ row.name }}</td>
                                            <td>{{ row.created_at }}</td>
                                            <td class="text-center">
                                                <span :class="`badge text-bg-${row.level.color}`">{{ row.level.name }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge"
                                                    :class="{ 'text-bg-success': row.active, 'text-bg-danger': !row.active }">
                                                    {{ row.active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <RouterLink
                                                        :to="{ name: 'teacher.quizzes.edit', params: { id: row.id } }"
                                                        class="btn btn-sm btn-alt-success">
                                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                                    </RouterLink>
                                                    <button type="button" v-on:click="$event => deleteQuiz(row)"
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