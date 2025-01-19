<script setup>
import { Link } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';

defineProps({
    columns: Array,
    data: Array,
    actions: {
        type: Object,
        default: () => ({})
    },
    can: {
        type: Object,
        default: () => ({})
    }
});

defineEmits(['delete']);
</script>

<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th v-for="column in columns"
                            :key="column.key"
                            :style="column.width ? { width: column.width } : {}"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ column.label }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in data" :key="item.id">
                        <td v-for="column in columns"
                            :key="column.key"
                            class="px-6 py-4 whitespace-nowrap"
                        >
                            <slot :name="column.key" :item="item">
                                <span class="text-sm text-gray-900">{{ item[column.key] }}</span>
                            </slot>
                        </td>
                        <td v-if="actions" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <Link
                                v-if="can.edit"
                                :href="route(actions.edit, item.id)"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                Editar
                            </Link>

                            <DangerButton
                                v-if="can.delete"
                                class="inline-flex"
                                size="sm"
                                @click="$emit('delete', item)"
                            >
                                Excluir
                            </DangerButton>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
