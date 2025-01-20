<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Autocomplete from '@/Components/Autocomplete.vue';
import { computed } from 'vue';

const props = defineProps({
    parents: Array,
    parent_id: Number
});

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
    mother_phone: '',
    user_id: props.parent_id || ''
});

const submit = () => {
    form.post(route('children.store'), {
        preserveScroll: true,
        onSuccess: () => {
            window.location = route('children.index');
        },
        onError: (errors) => {
            console.log('Erros:', errors);
        }
    });
};

const ethnicityOptions = [
    { value: 'branco', label: 'Branco' },
    { value: 'pardo', label: 'Pardo' },
    { value: 'negro', label: 'Negro' },
    { value: 'indigena', label: 'Indígena' },
    { value: 'amarelo', label: 'Amarelo' }
];

const genderOptions = [
    { value: 'masculino', label: 'Masculino' },
    { value: 'feminino', label: 'Feminino' }
];

const stateOptions = [
    { value: 'AC', label: 'Acre' },
    { value: 'AL', label: 'Alagoas' },
    { value: 'AP', label: 'Amapá' },
    { value: 'AM', label: 'Amazonas' },
    { value: 'BA', label: 'Bahia' },
    { value: 'CE', label: 'Ceará' },
    { value: 'DF', label: 'Distrito Federal' },
    { value: 'ES', label: 'Espírito Santo' },
    { value: 'GO', label: 'Goiás' },
    { value: 'MA', label: 'Maranhão' },
    { value: 'MT', label: 'Mato Grosso' },
    { value: 'MS', label: 'Mato Grosso do Sul' },
    { value: 'MG', label: 'Minas Gerais' },
    { value: 'PA', label: 'Pará' },
    { value: 'PB', label: 'Paraíba' },
    { value: 'PR', label: 'Paraná' },
    { value: 'PE', label: 'Pernambuco' },
    { value: 'PI', label: 'Piauí' },
    { value: 'RJ', label: 'Rio de Janeiro' },
    { value: 'RN', label: 'Rio Grande do Norte' },
    { value: 'RS', label: 'Rio Grande do Sul' },
    { value: 'RO', label: 'Rondônia' },
    { value: 'RR', label: 'Roraima' },
    { value: 'SC', label: 'Santa Catarina' },
    { value: 'SP', label: 'São Paulo' },
    { value: 'SE', label: 'Sergipe' },
    { value: 'TO', label: 'Tocantins' }
];

// Nome do pai atual para o display inicial (apenas se for pai)
const parentName = computed(() => {
    return props.parents?.find(p => p.id === form.user_id)?.name || '';
});
</script>

<template>
    <Head title="Nova Criança" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nova Criança</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Dados Pessoais -->
                            <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                                <h3 class="text-lg font-medium">Dados Pessoais</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nome -->
                                    <div>
                                        <InputLabel for="name" value="Nome" />
                                        <TextInput
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.name" class="mt-2" />
                                    </div>

                                    <!-- Data de Nascimento -->
                                    <div>
                                        <InputLabel for="birth_date" value="Data de Nascimento" />
                                        <TextInput
                                            id="birth_date"
                                            v-model="form.birth_date"
                                            type="date"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.birth_date" class="mt-2" />
                                    </div>

                                    <!-- Etnia -->
                                    <div>
                                        <InputLabel for="ethnicity" value="Etnia" />
                                        <SelectInput
                                            id="ethnicity"
                                            v-model="form.ethnicity"
                                            :options="ethnicityOptions"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.ethnicity" class="mt-2" />
                                    </div>

                                    <!-- Gênero -->
                                    <div>
                                        <InputLabel for="gender" value="Gênero" />
                                        <SelectInput
                                            id="gender"
                                            v-model="form.gender"
                                            :options="genderOptions"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.gender" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Endereço -->
                            <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                                <h3 class="text-lg font-medium">Endereço</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Endereço -->
                                    <div>
                                        <InputLabel for="address" value="Endereço" />
                                        <TextInput
                                            id="address"
                                            v-model="form.address"
                                            type="text"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.address" class="mt-2" />
                                    </div>

                                    <!-- Número -->
                                    <div>
                                        <InputLabel for="address_number" value="Número" />
                                        <TextInput
                                            id="address_number"
                                            v-model="form.address_number"
                                            type="text"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.address_number" class="mt-2" />
                                    </div>

                                    <!-- Complemento -->
                                    <div>
                                        <InputLabel for="complement" value="Complemento" />
                                        <TextInput
                                            id="complement"
                                            v-model="form.complement"
                                            type="text"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError :message="form.errors.complement" class="mt-2" />
                                    </div>

                                    <!-- Bairro -->
                                    <div>
                                        <InputLabel for="neighborhood" value="Bairro" />
                                        <TextInput
                                            id="neighborhood"
                                            v-model="form.neighborhood"
                                            type="text"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.neighborhood" class="mt-2" />
                                    </div>

                                    <!-- Cidade -->
                                    <div>
                                        <InputLabel for="city" value="Cidade" />
                                        <TextInput
                                            id="city"
                                            v-model="form.city"
                                            type="text"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.city" class="mt-2" />
                                    </div>

                                    <!-- Estado -->
                                    <div>
                                        <InputLabel for="state" value="Estado" />
                                        <SelectInput
                                            id="state"
                                            v-model="form.state"
                                            :options="stateOptions"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.state" class="mt-2" />
                                    </div>

                                    <!-- CEP -->
                                    <div>
                                        <InputLabel for="zipcode" value="CEP" />
                                        <TextInput
                                            id="zipcode"
                                            v-model="form.zipcode"
                                            type="text"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.zipcode" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Dados dos Pais -->
                            <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                                <h3 class="text-lg font-medium">Dados dos Pais</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nome do Pai -->
                                    <div>
                                        <InputLabel for="father_name" value="Nome do Pai" />
                                        <TextInput
                                            id="father_name"
                                            v-model="form.father_name"
                                            type="text"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError :message="form.errors.father_name" class="mt-2" />
                                    </div>

                                    <!-- Telefone do Pai -->
                                    <div>
                                        <InputLabel for="father_phone" value="Telefone do Pai" />
                                        <TextInput
                                            id="father_phone"
                                            v-model="form.father_phone"
                                            type="text"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError :message="form.errors.father_phone" class="mt-2" />
                                    </div>

                                    <!-- Nome da Mãe -->
                                    <div>
                                        <InputLabel for="mother_name" value="Nome da Mãe" />
                                        <TextInput
                                            id="mother_name"
                                            v-model="form.mother_name"
                                            type="text"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError :message="form.errors.mother_name" class="mt-2" />
                                    </div>

                                    <!-- Telefone da Mãe -->
                                    <div>
                                        <InputLabel for="mother_phone" value="Telefone da Mãe" />
                                        <TextInput
                                            id="mother_phone"
                                            v-model="form.mother_phone"
                                            type="text"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError :message="form.errors.mother_phone" class="mt-2" />
                                    </div>

                                    <!-- Usuário Responsável -->
                                    <div v-if="parents">
                                        <InputLabel for="user_id" value="Usuário Responsável *" />
                                        <Autocomplete
                                            v-model="form.user_id"
                                            :display-value="parentName"
                                            url="/users/search"
                                            placeholder="Buscar responsável..."
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.user_id" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <Link
                                    :href="route('children.index')"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                                >
                                    Voltar
                                </Link>

                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
                                </PrimaryButton>

                                <!-- Mensagem de sucesso -->
                                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                                    Salvo com sucesso!
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
