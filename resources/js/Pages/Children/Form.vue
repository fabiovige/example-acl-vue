<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Autocomplete from '@/Components/Autocomplete.vue';
import AddressForm from '@/Components/AddressForm.vue';
import { computed } from 'vue';

const props = defineProps({
    child: {
        type: Object,
        default: () => ({})
    },
    parents: Array,
    parent_id: Number,
    employees: Array,
    title: String,
    mode: {
        type: String,
        default: 'create'
    }
});

// Função para formatar a data
const formatDate = (isoDate) => {
    if (!isoDate) return '';
    return isoDate.split('T')[0];
};

const form = useForm({
    name: props.child?.name || '',
    birth_date: formatDate(props.child?.birth_date) || '',
    ethnicity: props.child?.ethnicity || '',
    gender: props.child?.gender || '',
    address: props.child?.address || '',
    address_number: props.child?.address_number || '',
    complement: props.child?.complement || '',
    neighborhood: props.child?.neighborhood || '',
    city: props.child?.city || '',
    state: props.child?.state || '',
    zipcode: props.child?.zipcode || '',
    father_name: props.child?.father_name || '',
    father_phone: props.child?.father_phone || '',
    mother_name: props.child?.mother_name || '',
    mother_phone: props.child?.mother_phone || '',
    parent_id: props.child?.parent_id || props.parent_id || '',
    employee_id: props.child?.employee_id || ''
});

const submit = () => {
    if (props.mode === 'edit') {
        form.put(route('children.update', props.child.id), {
            preserveScroll: true
        });
    } else {
        form.post(route('children.store'), {
            preserveScroll: true,
            onSuccess: () => {
                window.location = route('children.index');
            }
        });
    }
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

// Nome do responsável para o display
const parentName = computed(() => {
    return props.parents?.find(p => p.id === form.parent_id)?.name || '';
});

// Nome do funcionário para o display
const employeeName = computed(() => {
    return props.employees?.find(p => p.id === form.employee_id)?.name || '';
});

const addressData = computed(() => ({
    address: form.address,
    address_number: form.address_number,
    complement: form.complement,
    neighborhood: form.neighborhood,
    city: form.city,
    state: form.state,
    zipcode: form.zipcode
}));

const updateAddress = (newAddress) => {
    form.address = newAddress.address;
    form.address_number = newAddress.address_number;
    form.complement = newAddress.complement;
    form.neighborhood = newAddress.neighborhood;
    form.city = newAddress.city;
    form.state = newAddress.state;
    form.zipcode = newAddress.zipcode;
};
</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
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
                            <AddressForm
                                v-model:address="addressData"
                                :errors="form.errors"
                                @update:address="updateAddress"
                            />

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
                                </div>
                            </div>

                            <!-- Responsáveis -->
                            <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                                <h3 class="text-lg font-medium">Responsáveis</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Responsável -->
                                    <div class="relative z-50">
                                        <InputLabel for="parent_id" value="Responsável" />
                                        <Autocomplete
                                            v-model="form.parent_id"
                                            :display-value="parentName"
                                            url="/users/search?role=Pais"
                                            placeholder="Buscar responsável..."
                                            class="mt-1 block w-full"
                                        />
                                        <InputError :message="form.errors.parent_id" class="mt-2" />
                                    </div>

                                    <!-- Funcionário Responsável -->
                                    <div class="relative z-40">
                                        <InputLabel for="employee_id" value="Funcionário Responsável" />
                                        <Autocomplete
                                            v-model="form.employee_id"
                                            :display-value="employeeName"
                                            url="/users/search?role=Funcionario"
                                            placeholder="Buscar funcionário..."
                                            class="mt-1 block w-full"
                                        />
                                        <InputError :message="form.errors.employee_id" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
                                </PrimaryButton>

                                <Link
                                    :href="route('children.index')"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                                >
                                    Voltar
                                </Link>

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
