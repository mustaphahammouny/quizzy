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

import useVuelidate from "@vuelidate/core";
import { required, minLength } from "@vuelidate/validators";

import alert from '@/support/alert';
import dataset from '@/support/dataset';

const router = useRouter();
const route = useRoute();

const state = reactive({
    name: null,
    active: false,
});

const cols = reactive([
    {
        name: "question",
        field: "question",
        sort: "",
        with: "50%",
    },
    {
        name: "type",
        field: "type",
        sort: "",
        with: "50%",
    },
]);

const questions = ref([]);

const rules = computed(() => {
    return {
        name: {
            required,
            minLength: minLength(3),
        },
    };
});

const sortBy = computed(() => {
    return dataset.sortBy(cols);
});

const v$ = useVuelidate(rules, state);

const save = async () => {
    const result = await v$.value.$validate();

    if (!result) {
        return;
    }

    try {
        await http.put(`api/teacher/quizzes/${route.params.id}`, state);

        router.push({ name: `teacher.quizzes.index` });
    } catch (error) {
        console.log(error.response.data.message);
    }
};

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

        state.name = response.data.data.name;
        state.active = response.data.data.active;
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
    <BasePageHeading title="Edit quiz">
        <template #extra>
            <RouterLink :to="{ name: 'teacher.quizzes.index' }" class="btn btn-alt-secondary" v-click-ripple>
                <i class="fa fa-fw fa-undo opacity-50 me-1"></i>
                Back
            </RouterLink>
        </template>
    </BasePageHeading>

    <div class="content">
        <BaseBlock content-full>
            <form @submit.prevent="save">
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="name" name="name" placeholder="name"
                        :class="{ 'is-invalid': v$.name.$errors.length }" v-model="state.name" @blur="v$.name.$touch" />
                    <label class="first-capitalize" for="name">name</label>
                    <div v-if="v$.name.$errors.length" class="invalid-feedback animated fadeIn">
                        {{ v$.name.$errors[0].$message }}
                    </div>
                </div>
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active" name="active" v-model="state.active">
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-alt-primary">
                        <i class="fa fa-fw fa-save me-1 opacity-50"></i> Save
                    </button>
                </div>
            </form>
        </BaseBlock>

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
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <RouterLink
                                                        :to="{ name: 'teacher.questions.edit', params: { quizId: route.params.id, id: row.id } }"
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
