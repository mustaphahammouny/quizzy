<script setup></script>

<template>
    <BasePageHeading title="Dashboard" subtitle="Welcome Admin!">
        <template #extra>
            <button type="button" class="btn btn-alt-primary" v-click-ripple>
                <i class="fa fa-plus opacity-50 me-1"></i>
                New Project
            </button>
        </template>
    </BasePageHeading>

    <div class="content">
        <div class="row items-push">
            <div class="col">
                <BaseBlock title="Block" class="h-100 mb-0">
                    <p>...</p>
                </BaseBlock>
            </div>
        </div>
    </div>
</template>
