<script setup>
import { Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import AddressForm from '@/Components/AddressForm.vue';
import { computed } from 'vue';

const props = defineProps({
    form: Object
});

defineEmits(['submit']);

// Função para formatar a data
const formatDate = (isoDate) => {
    if (!isoDate) return '';
    return isoDate.split('T')[0];
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

const addressData = computed(() => ({
    address: props.form.address || '',
    address_number: props.form.address_number || '',
    complement: props.form.complement || '',
    neighborhood: props.form.neighborhood || '',
    city: props.form.city || '',
    state: props.form.state || '',
    zipcode: props.form.zipcode || ''
}));

const updateAddress = (newAddress) => {
    props.form.address = newAddress.address;
    props.form.address_number = newAddress.address_number;
    props.form.complement = newAddress.complement || null;
    props.form.neighborhood = newAddress.neighborhood;
    props.form.city = newAddress.city;
    props.form.state = newAddress.state;
    props.form.zipcode = newAddress.zipcode;
};
</script>

<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form @submit.prevent="$emit('submit')" class="space-y-6">
                        <!-- Dados Pessoais -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                            <h3 class="text-lg font-medium">Dados Pessoais</h3>
                            <p class="text-sm text-gray-500">Campos marcados com * são obrigatórios</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nome -->
                                <div>
                                    <InputLabel for="name" value="Nome *" />
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
                                    <InputLabel for="birth_date" value="Data de Nascimento *" />
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
                                    <InputLabel for="ethnicity" value="Etnia *" />
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
                                    <InputLabel for="gender" value="Gênero *" />
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
</template>
