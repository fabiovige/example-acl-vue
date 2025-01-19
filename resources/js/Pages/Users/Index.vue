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
    users: Object,
    title: String,
    can: Object,
});

// Usando os composables
const { showMessage, showError, messageText, errorText } = useFlashMessages();

const {
    showModal: confirmingUserDeletion,
    itemToDelete: userBeingDeleted,
    form,
    confirmDelete: confirmUserDeletion,
    handleDelete: deleteUser,
    closeModal
} = useConfirmDelete({
    routeName: 'users.destroy',
    onSuccess: () => {
        messageText.value = 'Usuário excluído com sucesso.';
        showMessage.value = true;
        setTimeout(() => showMessage.value = false, 4000);
    },
    onError: () => {
        errorText.value = 'Erro ao excluir usuário.';
        showError.value = true;
        setTimeout(() => showError.value = false, 4000);
    }
});

// Configuração da tabela
const columns = [
    { key: 'id', label: 'ID', width: '60px' },
    { key: 'name', label: 'Nome' },
    { key: 'email', label: 'Email' },
    { key: 'roles', label: 'Funções' },
    { key: 'created_at', label: 'Created At', width: '160px' }
];
</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
                <Link
                    v-if="can.users_create"
                    :href="route('users.create')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Novo Usuário
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
                    :data="users.data"
                    :actions="{ edit: 'users.edit' }"
                    :can="{ edit: can.users_edit, delete: can.users_delete }"
                    @delete="confirmUserDeletion"
                >
                    <!-- Slot customizado para roles -->
                    <template #roles="{ item }">
                        <div class="flex flex-wrap gap-1">
                            <span v-for="role in item.roles"
                                  :key="role.id"
                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                            >
                                {{ role.name }}
                            </span>
                            <span v-if="item.roles.length === 0" class="text-sm text-gray-500 italic">
                                Nenhuma função
                            </span>
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
        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Tem certeza que deseja excluir este usuário?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Após excluído, todos os dados relacionados a este usuário serão permanentemente removidos.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Excluir Usuário
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
.space-x-2 > * + * {
    margin-left: 0.5rem;
}
</style>
