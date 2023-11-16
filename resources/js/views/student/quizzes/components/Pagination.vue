<script setup>
const props = defineProps({
    meta: {
        type: Object,
        required: true,
    },
    paginate: {
        type: Function,
        required: true,
    }
});
</script>

<template>
    <div class="row">
        <div class="col text-end">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    <li class="page-item" :class="{
                        'disabled': meta.current_page === 1
                    }">
                        <button class="page-link" v-on:click="paginate(meta.current_page - 1)" tabindex="-1"
                            aria-label="Previous" :disabled="meta.current_page === 1">
                            <span aria-hidden="true">
                                <i class="fa fa-angle-double-left"></i>
                            </span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                    </li>
                    <li class="page-item active">
                        <button class="page-link">{{ meta.current_page }}</button>
                    </li>
                    <li class="page-item" :class="{
                        'disabled': meta.current_page === meta.last_page
                    }">
                        <button class="page-link" v-on:click="paginate(meta.current_page + 1)" aria-label="Next"
                            :disabled="meta.current_page === meta.last_page">
                            <span aria-hidden="true">
                                <i class="fa fa-angle-double-right"></i>
                            </span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>