<script setup>
import { reactive, computed, ref, onBeforeMount, onMounted } from "vue";
import { useRouter } from "vue-router";

import useVuelidate from "@vuelidate/core";
import { required, minLength, numeric } from "@vuelidate/validators";
import vueFilePond from "vue-filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

import { useNotificationStore } from "@/stores/notification.store";

const router = useRouter();
const notification = useNotificationStore();

const props = defineProps({
    title: {
        type: String,
        default: "",
    },
    quiz: {
        type: Object,
        default: null,
    },
});

const levels = ref([]);
const pond = ref(null);

const state = reactive({
    name: props.quiz?.name ?? null,
    tags: props.quiz?.tags ?? [],
    level: props.quiz?.level.value ?? null,
    image: null,
    active: props.quiz?.active ?? false,
    _method: props.quiz ? "put" : "post",
});

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview
);

const rules = computed(() => {
    return {
        name: {
            required,
            minLength: minLength(3),
        },
        level: {
            required,
            numeric,
        },
        image: {
            required,
        },
    };
});

const v$ = useVuelidate(rules, state);

const addFile = (error, file) => {
    if (error) {
        console.log(error);
        return;
    }

    if (file.file instanceof Blob) {
        state.image = new File([file.file], file.file.name);
        return;
    }

    state.image = file.file;
};

const removeFile = (error, file) => {
    if (error) {
        console.log(error);
        return;
    }

    state.image = null;
};

const save = async () => {
    const result = await v$.value.$validate();

    if (!result) {
        return;
    }

    try {
        const headers = { "Content-Type": "multipart/form-data" };
        let title = "Quiz addedd successfully!";
        let endpoint = "api/teacher/quizzes";

        if (props.quiz) {
            title = "Quiz updated successfully!";
            endpoint = `${endpoint}/${props.quiz.id}`;
        }

        await http.post(endpoint, state, headers);

        notification.setMessage({ title: title, icon: "success" });

        router.push({ name: `teacher.quizzes.index` });
    } catch (error) {
        console.log(error.response.data.message);
    }
};

onBeforeMount(async () => {
    try {
        const response = await http.get("api/levels/select");

        levels.value = response.data.data;
    } catch (error) {
        console.log(error.response.data.message);
    }
});

onMounted(() => {
    if (props.quiz) {
        pond.value.addFile(props.quiz.image);
    }
});
</script>

<template>
    <BasePageHeading :title="title">
        <template #extra>
            <RouterLink
                :to="{ name: 'teacher.quizzes.index' }"
                class="btn btn-alt-secondary"
                v-click-ripple
            >
                <i class="fa fa-fw fa-undo opacity-50 me-1"></i>
                Back
            </RouterLink>
        </template>
    </BasePageHeading>

    <div class="content">
        <BaseBlock content-full>
            <form @submit.prevent="save" class="w-100">
                <div class="row items-push">
                    <div class="col-md-4 col-lg-5 my-auto text-center">
                        <FilePond
                            ref="pond"
                            name="image"
                            label-idle="Drop files here..."
                            accepted-file-types="image/jpeg, image/png"
                            @addfile="addFile"
                            @removefile="removeFile"
                        />
                        <div
                            v-if="v$.image.$errors.length"
                            class="text-danger animated fadeIn"
                        >
                            {{ v$.image.$errors[0].$message }}
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-7">
                        <div class="form-floating mb-4">
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                placeholder="name"
                                :class="{
                                    'is-invalid': v$.name.$errors.length,
                                }"
                                v-model="state.name"
                                @blur="v$.name.$touch"
                            />
                            <label class="first-capitalize" for="name">
                                name
                            </label>
                            <div
                                v-if="v$.name.$errors.length"
                                class="invalid-feedback animated fadeIn"
                            >
                                {{ v$.name.$errors[0].$message }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <VSelect
                                placeholder="Level"
                                :options="levels"
                                :reduce="(level) => level.value"
                                :class="{
                                    'is-invalid': v$.level.$errors.length,
                                }"
                                v-model="state.level"
                            >
                            </VSelect>
                            <div
                                v-if="v$.level.$errors.length"
                                class="invalid-feedback animated fadeIn"
                            >
                                {{ v$.level.$errors[0].$message }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <VSelect
                                placeholder="Tags"
                                taggable
                                multiple
                                v-model="state.tags"
                            ></VSelect>
                        </div>
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="active"
                                    name="active"
                                    v-model="state.active"
                                />
                                <label class="form-check-label" for="active">
                                    Active
                                </label>
                            </div>
                        </div>
                        <div class="text-center">
                            <button
                                type="submit"
                                class="btn btn-lg btn-alt-primary"
                            >
                                <i class="fa fa-fw fa-save me-1 opacity-50"></i>
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </BaseBlock>
    </div>
</template>

<style lang="scss">
@import "filepond/dist/filepond.min.css";
@import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
</style>
