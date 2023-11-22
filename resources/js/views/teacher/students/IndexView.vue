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

import dataset from '@/support/dataset';

const cols = reactive([
    {
        name: "full name",
        field: "full_name",
        sort: "",
        with: "50%",
    },
    {
        name: "email",
        field: "email",
        sort: "",
        with: "50%",
    },
]);

const students = ref([]);

const sortBy = computed(() => {
    return dataset.sortBy(cols);
});

onBeforeMount(async () => {
    try {
        const response = await http.get("api/teacher/students");

        students.value = response.data.data;
    } catch (error) {
        console.log(error.response.data.message);
    }
});

onMounted(() => {
    dataset.css();
});
</script>

<template>
    <BasePageHeading title="Students" />

    <div class="content">
        <BaseBlock content-full>
            <Dataset v-slot="{ ds }" :ds-data="students" :ds-sortby="sortBy" :ds-search-in="['full_name']">
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
                                    </tr>
                                </thead>
                                <DatasetItem tag="tbody" class="fs-sm">
                                    <template #default="{ row, rowIndex }">
                                        <tr>
                                            <td>{{ row.full_name }}</td>
                                            <td>{{ row.email }}</td>
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