<script setup>
import Form from './Form.vue';

defineProps({
    parents: Array,
    parent_id: Number,
    employees: Array
});
</script>

<template>
    <Form
        :parents="parents"
        :parent_id="parent_id"
        :employees="employees"
        title="Nova Criança"
        mode="create"
    />
</template>
