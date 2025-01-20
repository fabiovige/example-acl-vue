<script setup>
import { ref, watch } from 'vue';
import axios from 'axios'; // Vamos usar axios para requisições HTTP

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
        default: 'Pesquisar...'
    },
    url: {
        type: String,
        required: true
    }
});

const emit = defineEmits(['update:modelValue']);

const searchQuery = ref(props.displayValue);
const results = ref([]);
const showResults = ref(false);
const isLoading = ref(false);

// Nossa própria implementação do debounce
function debounce(fn, delay) {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn.apply(this, args), delay);
    };
}

// Debounce a busca para evitar muitas requisições
const search = debounce(async (query) => {
    if (!query) {
        results.value = [];
        showResults.value = false;
        return;
    }

    isLoading.value = true;
    try {
        const response = await axios.get(props.url, {
            params: { search: query }
        });
        results.value = response.data;
        showResults.value = true;
    } catch (error) {
        console.error('Erro na busca:', error);
        results.value = [];
    } finally {
        isLoading.value = false;
    }
}, 300);

watch(searchQuery, (newQuery) => {
    search(newQuery);
});

const selectItem = (item) => {
    searchQuery.value = item.name;
    emit('update:modelValue', item.id);
    showResults.value = false;
};
</script>

<template>
    <div class="relative">
        <input
            type="text"
            v-model="searchQuery"
            :placeholder="placeholder"
            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            @focus="showResults = true"
        />

        <!-- Loading indicator -->
        <div v-if="isLoading" class="absolute right-3 top-2">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Results dropdown -->
        <div v-if="showResults && results.length > 0"
             class="absolute z-10 mt-1 w-full bg-white rounded-md shadow-lg max-h-60 overflow-auto">
            <ul class="py-1">
                <li v-for="item in results"
                    :key="item.id"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                    @click="selectItem(item)"
                >
                    <div class="text-sm text-gray-700">{{ item.name }}</div>
                    <div class="text-xs text-gray-500">{{ item.email }}</div>
                </li>
            </ul>
        </div>

        <!-- No results message -->
        <div v-if="showResults && searchQuery && results.length === 0 && !isLoading"
             class="absolute z-10 mt-1 w-full bg-white rounded-md shadow-lg">
            <div class="px-4 py-2 text-sm text-gray-700">
                Nenhum resultado encontrado
            </div>
        </div>
    </div>
</template>
