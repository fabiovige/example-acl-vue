import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useFlashMessages() {
    const showMessage = ref(false);
    const showError = ref(false);
    const messageText = ref('');
    const errorText = ref('');

    const flashMessage = computed(() => usePage().props.flash?.message);
    const flashError = computed(() => usePage().props.flash?.error);

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

    onMounted(showTemporaryMessage);

    return {
        showMessage,
        showError,
        messageText,
        errorText,
        showTemporaryMessage
    };
}
