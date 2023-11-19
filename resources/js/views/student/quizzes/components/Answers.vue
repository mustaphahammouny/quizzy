<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        required: true,
    },
    answers: {
        type: Array,
        required: true,
    },
    modelValue: {
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);

const type = computed(() => {
    return props.type.toLowerCase();
});

const value = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit('update:modelValue', value);
    }
});
</script>

<template>
    <div class="row row-cols-1 items-push">
        <div v-for="answer in answers" :key="answer.id" class="col">
            <div class="form-check form-block">
                <input :type="type" class="form-check-input" :id="`answer-${answer.id}`" v-model="value" :value="answer.id">
                <label class="form-check-label" :for="`answer-${answer.id}`">
                    {{ answer.answer }}
                </label>
            </div>
        </div>
    </div>
</template>