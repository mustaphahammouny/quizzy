<script setup>
import Answer from "@/components/Answer.vue";
</script>

<template>
    <div class="row g-4 align-items-center justify-content-center mb-4">
        <div class="col-6">
            <div class="form-floating">
                <input type="text" class="form-control" name="questions[]" placeholder="question" />
                <label class="first-capitalize" for="question">question</label>
            </div>
        </div>
        <div class="col-3">
            <div class="form-floating">
                <select class="form-select" name="types[]" aria-label="type">
                    <option value="1">Radio</option>
                    <option value="2">Checkbox</option>
                </select>
                <label class="first-capitalize" for="type">type</label>
            </div>
        </div>
        <div class="col-1">
            <button type="button" v-on:click="" class="btn btn-alt-danger">
                <i class="fa fa-fw fa-trash-alt"></i>
            </button>
        </div>
    </div>

    <BaseBlock title="Answer" class="mb-0" content-full>
        <template #options>
            <button type="button" class="btn btn-alt-primary">
                <i class="fa fa-fw fa-plus opacity-50 me-1"></i>
                Add
            </button>
        </template>
        <div class="mb-4">
            <Answer />
        </div>
    </BaseBlock>
</template>