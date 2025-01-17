<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    role: Object,
    permissions: Array,
});

const form = useForm({
    name: props.role.name,
    permissions: props.role.permissions.map(p => p.id),
});

const togglePermission = (id) => {
    const index = form.permissions.indexOf(id);
    if (index === -1) {
        form.permissions.push(id);
    } else {
        form.permissions.splice(index, 1);
    }
};

const updateRole = () => {
    form.put(route('roles.update', props.role.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Editar Função" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles / Editar</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section class="max-w-xl">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Editar Função</h2>
                        </header>

                        <form @submit.prevent="updateRole" class="mt-6 space-y-6">
                            <div>
                                <InputLabel for="name" value="Nome da Função" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel value="Permissões" />
                                <div class="mt-4 space-y-2">
                                    <div v-for="permission in permissions" :key="permission.id"
                                         class="flex items-center">
                                        <Checkbox
                                            :id="`permission-${permission.id}`"
                                            :checked="form.permissions.includes(permission.id)"
                                            @change="togglePermission(permission.id)"
                                            :name="`permissions[${permission.id}]`"
                                        />
                                        <label :for="`permission-${permission.id}`"
                                               class="ml-2 text-sm text-gray-600">
                                            {{ permission.name }}
                                        </label>
                                    </div>
                                </div>
                                <InputError :message="form.errors.permissions" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">
                                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
                                </PrimaryButton>

                                <Link
                                    :href="route('roles.index')"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                                >
                                    Voltar
                                </Link>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                                        Salvo com sucesso!
                                    </p>
                                </Transition>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
