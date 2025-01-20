<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    modelValue: {
        type: [String, Number, null],
        required: true,
    },
    displayValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Selecione...'
    },
    url: {
        type: String,
        required: true
    }
});

const emit = defineEmits(['update:modelValue']);

const items = ref([]);
const isLoading = ref(false);

// Carrega todos os dados ao montar o componente
const loadAllData = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(props.url);
        items.value = response.data;
    } catch (error) {
        console.error('Erro ao carregar dados:', error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    loadAllData();
});

const handleChange = (event) => {
    emit('update:modelValue', event.target.value);
};
</script>

<template>
    <div class="relative">
        <select
            :value="modelValue"
            @change="handleChange"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            :required="required"
        >
            <option value="">{{ placeholder }}</option>
            <option v-for="item in items"
                    :key="item.id"
                    :value="item.id"
            >
                {{ item.name }}
            </option>
        </select>

        <!-- Loading indicator -->
        <div v-if="isLoading" class="absolute right-3 top-2">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</template>
