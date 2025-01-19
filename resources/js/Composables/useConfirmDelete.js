import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

export function useConfirmDelete(options = {}) {
    const showModal = ref(false);
    const itemToDelete = ref(null);
    const form = useForm({});

    const confirmDelete = (item) => {
        itemToDelete.value = item;
        showModal.value = true;
    };

    const handleDelete = () => {
        form.delete(route(options.routeName, itemToDelete.value.id), {
            preserveScroll: true,
            onSuccess: options.onSuccess,
            onError: options.onError,
        });
    };

    const closeModal = () => {
        showModal.value = false;
        itemToDelete.value = null;
    };

    return {
        showModal,
        itemToDelete,
        form,
        confirmDelete,
        handleDelete,
        closeModal
    };
}
