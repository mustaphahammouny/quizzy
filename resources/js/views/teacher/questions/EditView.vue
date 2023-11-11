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
import { required, minLength, numeric } from "@vuelidate/validators";

import alert from '@/support/alert';
import dataset from '@/support/dataset';

const router = useRouter();
const route = useRoute();

const state = reactive({
    question: null,
    type: null,
    time: null,
});

const cols = reactive([
    {
        name: "answer",
        field: "answer",
        sort: "",
        with: "100%",
    },
]);

const answers = ref([]);

const rules = computed(() => {
    return {
        question: {
            required,
            minLength: minLength(3),
        },
        type: {
            required,
            numeric,
        },
        time: {
            required,
            numeric,
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
        await http.put(`api/teacher/questions/${route.params.id}`, state);

        router.push({ name: `teacher.quizzes.edit`, params: { id: route.params.quizId } });
    } catch (error) {
        console.log(error.response.data.message);
    }
};

const deleteAnswer = (question) => {
    alert.confirm({
        text: '',
        callback: async () => {
            try {
                await http.delete(`api/teacher/answers/${answer.id}`);

                answers.value = answers.value.filter((item) => item.id !== question.id);
            } catch (error) {
                console.log(error.response.message ?? error.response.data.message);
            }
        },
    });
};

onBeforeMount(async () => {
    try {
        const response = await http.get(`api/teacher/questions/${route.params.id}`);

        state.question = response.data.data.question;
        state.type = response.data.data.type.value;
        state.time = response.data.data.time;

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
    <BasePageHeading title="Edit question">
        <template #extra>
            <RouterLink :to="{ name: 'teacher.quizzes.edit', params: { id: route.params.quizId } }"
                class="btn btn-alt-secondary" v-click-ripple>
                <i class="fa fa-fw fa-undo opacity-50 me-1"></i>
                Back
            </RouterLink>
        </template>
    </BasePageHeading>

    <div class="content">
        <BaseBlock content-full>
            <form @submit.prevent="save">
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="question" name="question" placeholder="question"
                        :class="{ 'is-invalid': v$.question.$errors.length }" v-model="state.question"
                        @blur="v$.question.$touch" />
                    <label class="first-capitalize" for="question">question</label>
                    <div v-if="v$.question.$errors.length" class="invalid-feedback animated fadeIn">
                        {{ v$.question.$errors[0].$message }}
                    </div>
                </div>
                <div class="form-floating mb-4">
                    <select class="form-select" id="type" name="type" aria-label="type"
                        :class="{ 'is-invalid': v$.type.$errors.length }" v-model="state.type">
                        <option value="1">Radio</option>
                        <option value="2">Checkbox</option>
                    </select>
                    <label class="first-capitalize" for="type">type</label>
                    <div v-if="v$.type.$errors.length" class="invalid-feedback animated fadeIn">
                        {{ v$.type.$errors[0].$message }}
                    </div>
                </div>
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="time" name="time" placeholder="time"
                        :class="{ 'is-invalid': v$.time.$errors.length }" v-model="state.time" @blur="v$.time.$touch" />
                    <label class="first-capitalize" for="time">time</label>
                    <div v-if="v$.time.$errors.length" class="invalid-feedback animated fadeIn">
                        {{ v$.time.$errors[0].$message }}
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-alt-primary">
                        <i class="fa fa-fw fa-save me-1 opacity-50"></i> Save
                    </button>
                </div>
            </form>
        </BaseBlock>

        <BaseBlock title="Answers" content-full>
            <template #options>
                <RouterLink :to="{ name: 'teacher.answers.create', params: { id: route.params.id } }"
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
