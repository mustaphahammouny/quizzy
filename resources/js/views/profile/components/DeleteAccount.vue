<script setup>
const deleteAccount = () => {
    console.log('done');
};
</script>

<template>
    <BaseBlock title="Delete account" content-full>
        <p>
            Once your account is deleted, all of its resources and data will be
            permanently deleted. Before deleting your account, please download
            any data or information that you wish to retain.
        </p>
        <button class="btn btn-danger" @click="deleteAccount">
            <i class="fa fa-trash me-1"></i>
            Delete account
        </button>
    </BaseBlock>
</template>
