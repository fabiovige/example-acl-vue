<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { useFlashMessages } from '@/Composables/useFlashMessages.js';
import { useConfirmDelete } from '@/Composables/useConfirmDelete.js';

const props = defineProps({
    children: Object,
    title: String,
    can: Object,
});

// Usando os composables
const { showMessage, showError, messageText, errorText } = useFlashMessages();

const {
    showModal: confirmingChildDeletion,
    itemToDelete: childBeingDeleted,
    form,
    confirmDelete: confirmChildDeletion,
    handleDelete: deleteChild,
    closeModal
} = useConfirmDelete({
    routeName: 'children.destroy',
    onSuccess: () => {
        messageText.value = 'Criança excluída com sucesso.';
        showMessage.value = true;
        setTimeout(() => showMessage.value = false, 4000);
    },
    onError: () => {
        errorText.value = 'Erro ao excluir criança.';
        showError.value = true;
        setTimeout(() => showError.value = false, 4000);
    }
});

// Configuração da tabela
const columns = [
    { key: 'id', label: 'ID', width: '60px' },
    { key: 'name', label: 'Nome' },
    { key: 'formatted_birth_date', label: 'Data Nasc.', width: '100px' },
    { key: 'gender', label: 'Gênero', width: '100px' },
    { key: 'parent_info', label: 'Responsável' },
    { key: 'created_at', label: 'Cadastro', width: '160px' }
];
</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
                <Link
                    v-if="can.children_create"
                    :href="route('children.create')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Nova Criança
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Mensagens Flash -->
                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="transform -translate-y-2 opacity-0"
                    enter-to-class="transform translate-y-0 opacity-100"
                    leave-active-class="transition duration-300 ease-in"
                    leave-from-class="transform translate-y-0 opacity-100"
                    leave-to-class="transform -translate-y-2 opacity-0"
                >
                    <div v-if="showMessage" class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        <p>{{ messageText }}</p>
                    </div>
                </Transition>

                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="transform -translate-y-2 opacity-0"
                    enter-to-class="transform translate-y-0 opacity-100"
                    leave-active-class="transition duration-300 ease-in"
                    leave-from-class="transform translate-y-0 opacity-100"
                    leave-to-class="transform -translate-y-2 opacity-0"
                >
                    <div v-if="showError" class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        <p>{{ errorText }}</p>
                    </div>
                </Transition>

                <!-- DataTable Component -->
                <DataTable
                    :columns="columns"
                    :data="children.data"
                    :actions="{ edit: 'children.edit' }"
                    :can="{ edit: can.children_edit, delete: can.children_delete }"
                    @delete="confirmChildDeletion"
                >
                    <!-- Slot customizado para gênero -->
                    <template #gender="{ item }">
                        <span class="capitalize">{{ item.gender }}</span>
                    </template>

                    <!-- Slot customizado para informações do responsável -->
                    <template #parent_info="{ item }">
                        <div class="text-sm">
                            <p v-if="item.father_name">Pai: {{ item.father_name }}</p>
                            <p v-if="item.mother_name">Mãe: {{ item.mother_name }}</p>
                            <p v-if="item.parent" class="text-gray-500">{{ item.parent.name }}</p>
                        </div>
                    </template>

                    <!-- Slot customizado para created_at -->
                    <template #created_at="{ item }">
                        <span class="text-sm text-gray-500">
                            {{ new Date(item.created_at).toLocaleDateString() + ' ' + new Date(item.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                        </span>
                    </template>
                </DataTable>
            </div>
        </div>

        <!-- Modal de Confirmação -->
        <Modal :show="confirmingChildDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Tem certeza que deseja excluir esta criança?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Após excluída, todos os dados relacionados a esta criança serão permanentemente removidos.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteChild"
                    >
                        Excluir Criança
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
