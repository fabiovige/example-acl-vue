<script setup>
import { ref, watch, nextTick } from 'vue';
import axios from 'axios';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';

const props = defineProps({
    address: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['update:address']);

const isLoading = ref(false);
const zipcode = ref(props.address.zipcode || '');
const fieldsFromCep = ref({
    address: false,
    neighborhood: false,
    city: false,
    state: false
});

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

const addressNumberInput = ref(null);

const formatZipcode = (value) => {
    if (!value) return '';
    value = value.replace(/\D/g, '');
    return value.replace(/^(\d{5})(\d{3}).*/, '$1-$2');
};

const searchAddress = async (cep) => {
    if (cep.length < 8) return;

    isLoading.value = true;
    try {
        const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`);
        if (!response.data.erro) {
            fieldsFromCep.value = {
                address: !!response.data.logradouro,
                neighborhood: !!response.data.bairro,
                city: !!response.data.localidade,
                state: !!response.data.uf
            };

            emit('update:address', {
                ...props.address,
                zipcode: formatZipcode(cep),
                address: response.data.logradouro || props.address.address,
                neighborhood: response.data.bairro || props.address.neighborhood,
                city: response.data.localidade || props.address.city,
                state: response.data.uf || props.address.state,
            });

            nextTick(() => {
                addressNumberInput.value?.focus();
            });
        }
    } catch (error) {
        console.error('Erro ao buscar CEP:', error);
        fieldsFromCep.value = {
            address: false,
            neighborhood: false,
            city: false,
            state: false
        };
    } finally {
        isLoading.value = false;
    }
};

watch(zipcode, (newValue) => {
    const cleanZip = newValue.replace(/\D/g, '');
    if (cleanZip.length === 8) {
        searchAddress(cleanZip);
    }
});

const updateAddress = (field, value) => {
    const newAddress = {
        ...props.address,
        [field]: value
    };
    emit('update:address', newAddress);
};
</script>

<template>
    <div class="bg-gray-50 p-4 rounded-lg space-y-4">
        <h3 class="text-lg font-medium">Endereço</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- CEP -->
            <div class="relative">
                <InputLabel for="zipcode" value="CEP" />
                <TextInput
                    id="zipcode"
                    v-model="zipcode"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    maxlength="9"
                    @input="$event.target.value = formatZipcode($event.target.value)"
                />
                <div v-if="isLoading" class="absolute right-3 top-9">
                    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <InputError :message="errors.zipcode" class="mt-2" />
            </div>

            <!-- Endereço -->
            <div class="md:col-span-2">
                <InputLabel for="address" value="Endereço" />
                <TextInput
                    id="address"
                    v-model="address.address"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    :readonly="fieldsFromCep.address"
                    :class="{ 'bg-gray-100': fieldsFromCep.address }"
                    @input="updateAddress('address', $event.target.value)"
                />
                <InputError :message="errors.address" class="mt-2" />
            </div>

            <!-- Número -->
            <div>
                <InputLabel for="address_number" value="Número" />
                <TextInput
                    id="address_number"
                    ref="addressNumberInput"
                    v-model="address.address_number"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    @input="updateAddress('address_number', $event.target.value)"
                />
                <InputError :message="errors.address_number" class="mt-2" />
            </div>

            <!-- Complemento -->
            <div>
                <InputLabel for="complement" value="Complemento" />
                <TextInput
                    id="complement"
                    v-model="address.complement"
                    type="text"
                    class="mt-1 block w-full"
                    @input="updateAddress('complement', $event.target.value)"
                />
                <InputError :message="errors.complement" class="mt-2" />
            </div>

            <!-- Bairro -->
            <div>
                <InputLabel for="neighborhood" value="Bairro" />
                <TextInput
                    id="neighborhood"
                    v-model="address.neighborhood"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    :readonly="fieldsFromCep.neighborhood"
                    :class="{ 'bg-gray-100': fieldsFromCep.neighborhood }"
                    @input="updateAddress('neighborhood', $event.target.value)"
                />
                <InputError :message="errors.neighborhood" class="mt-2" />
            </div>

            <!-- Cidade -->
            <div>
                <InputLabel for="city" value="Cidade" />
                <TextInput
                    id="city"
                    v-model="address.city"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    :readonly="fieldsFromCep.city"
                    :class="{ 'bg-gray-100': fieldsFromCep.city }"
                    @input="updateAddress('city', $event.target.value)"
                />
                <InputError :message="errors.city" class="mt-2" />
            </div>

            <!-- Estado -->
            <div>
                <InputLabel for="state" value="Estado" />
                <SelectInput
                    id="state"
                    v-model="address.state"
                    :options="stateOptions"
                    class="mt-1 block w-full"
                    required
                    :disabled="fieldsFromCep.state"
                    :class="{ 'bg-gray-100': fieldsFromCep.state }"
                    @update:modelValue="updateAddress('state', $event)"
                />
                <InputError :message="errors.state" class="mt-2" />
            </div>
        </div>
    </div>
</template>
