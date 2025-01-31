<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Form from './Form.vue';

const props = defineProps({
    child: Object,
    parents: Array,
    employees: Array
});

const form = useForm({
    name: props.child.name,
    birth_date: props.child.birth_date.split('T')[0],
    ethnicity: props.child.ethnicity,
    gender: props.child.gender,
    address: props.child.address,
    address_number: props.child.address_number,
    complement: props.child.complement,
    neighborhood: props.child.neighborhood,
    city: props.child.city,
    state: props.child.state,
    zipcode: props.child.zipcode,
    father_name: props.child.father_name,
    father_phone: props.child.father_phone,
    mother_name: props.child.mother_name,
    mother_phone: props.child.mother_phone,
    parent_id: props.child.parent_id,
    employee_id: props.child.employee_id
});

const submit = () => {
    form.put(route('children.update', props.child.id));
};
</script>

<template>
    <Head title="Editar Criança" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Criança</h2>
        </template>

        <Form
            :form="form"
            :parents="parents"
            :employees="employees"
            @submit="submit"
        />
    </AuthenticatedLayout>
</template>
