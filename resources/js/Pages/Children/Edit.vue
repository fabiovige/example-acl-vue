<script setup>
import Form from './Form.vue';

defineProps({
    child: Object,
    parents: Array,
    employees: Array
});
</script>

<template>
    <Form
        :child="child"
        :parents="parents"
        :employees="employees"
        :title="`Editar ${child.name}`"
        mode="edit"
    />
</template>
