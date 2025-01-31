<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Form from './Form.vue';

const form = useForm({
    name: '',
    birth_date: '',
    ethnicity: '',
    gender: '',
    address: '',
    address_number: '',
    complement: '',
    neighborhood: '',
    city: '',
    state: '',
    zipcode: '',
    father_name: '',
    father_phone: '',
    mother_name: '',
    mother_phone: ''
});

const submit = () => {
    form.post(route('children.store'), {
        onSuccess: () => {
            // Será redirecionado pelo backend com a mensagem de sucesso
        },
        onError: (errors) => {
            console.error('Erros de validação:', errors);
        }
    });
};
</script>

<template>
    <Head title="Nova Criança" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nova Criança</h2>
        </template>

        <Form
            :form="form"
            @submit="submit"
        />
    </AuthenticatedLayout>
</template>
