<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';
import { useFlashMessages } from '@/Composables/useFlashMessages.js';
import { useConfirmDelete } from '@/Composables/useConfirmDelete.js';

const props = defineProps({
    permissions: Object,
    can: Object,
    filters: Object
});

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Nome' },
    { key: 'created_at', label: 'Criado em' }
];

const { showMessage, showError } = useFlashMessages();
const { confirmDelete, closeModal } = useConfirmDelete('permissions.destroy');
</script>

<template>
    <Head title="Permissões" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Permissões
                </h2>
                <Link
                    v-if="can.create"
                    :href="route('permissions.create')"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Nova Permissão
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Filtros -->
                        <div class="mb-4">
                            <input
                                type="text"
                                v-model="filters.search"
                                placeholder="Buscar permissões..."
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                        </div>

                        <DataTable
                            :columns="columns"
                            :data="permissions.data"
                            :actions="{ edit: 'permissions.edit' }"
                            :can="can"
                            @delete="confirmDelete"
                        />

                        <!-- Paginação -->
                        <div class="mt-6">
                            <template v-if="permissions.links.length > 3">
                                <div class="flex flex-wrap -mb-1">
                                    <template v-for="(link, key) in permissions.links" :key="key">
                                        <div v-if="link.url === null"
                                            class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                                            v-html="link.label"
                                        />
                                        <Link v-else
                                            class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                                            :class="{ 'bg-blue-700 text-white': link.active }"
                                            :href="link.url"
                                            v-html="link.label"
                                        />
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
