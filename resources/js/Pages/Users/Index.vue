<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    users: Object,
    title: String,
});

// Refs para controlar a visibilidade das mensagens
const showMessage = ref(false);
const showError = ref(false);
const messageText = ref('');
const errorText = ref('');

// Computed properties para pegar as mensagens do flash
const flashMessage = computed(() => usePage().props.flash?.message);
const flashError = computed(() => usePage().props.flash?.error);

// Função para mostrar mensagem temporária
const showTemporaryMessage = () => {
    if (flashMessage.value) {
        messageText.value = flashMessage.value;
        showMessage.value = true;
        setTimeout(() => {
            showMessage.value = false;
        }, 4000);
    }

    if (flashError.value) {
        errorText.value = flashError.value;
        showError.value = true;
        setTimeout(() => {
            showError.value = false;
        }, 4000);
    }
};

onMounted(() => {
    showTemporaryMessage();
});

// Estado do modal
const confirmingUserDeletion = ref(false);
const userBeingDeleted = ref(null);

// Form para delete
const form = useForm({});

const confirmUserDeletion = (user) => {
    userBeingDeleted.value = user;
    confirmingUserDeletion.value = true;
};

const deleteUser = () => {
    form.delete(route('users.destroy', userBeingDeleted.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            messageText.value = 'Usuário excluído com sucesso.';
            showMessage.value = true;
            setTimeout(() => {
                showMessage.value = false;
            }, 4000);
        },
        onError: () => {
            closeModal();
            errorText.value = 'Erro ao excluir usuário.';
            showError.value = true;
            setTimeout(() => {
                showError.value = false;
            }, 4000);
        },
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    userBeingDeleted.value = null;
};
</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
                <Link
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
                    <div v-if="showMessage"
                         class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
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
                    <div v-if="showError"
                         class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        <p>{{ errorText }}</p>
                    </div>
                </Transition>

                <!-- Tabela de Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 60px;">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nome
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Funções
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 160px;">
                                        Created At
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 160px;">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in users.data" :key="user.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ user.id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ user.name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            <span v-for="role in user.roles"
                                                  :key="role.id"
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                            >
                                                {{ role.name }}
                                            </span>
                                            <span v-if="user.roles.length === 0"
                                                  class="text-sm text-gray-500 italic"
                                            >
                                                Nenhuma função
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ new Date(user.created_at).toLocaleDateString() + ' ' + new Date(user.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <Link
                                            :href="route('users.edit', user.id)"
                                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                                        >
                                            Editar
                                        </Link>

                                        <DangerButton
                                            class="inline-flex"
                                            size="sm"
                                            @click="confirmUserDeletion(user)"
                                        >
                                            Excluir
                                        </DangerButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

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
