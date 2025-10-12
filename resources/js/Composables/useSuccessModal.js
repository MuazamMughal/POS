import { ref } from 'vue';

export default function useSuccessModal() {
    const show = ref(false);
    const title = ref('Success!');
    const message = ref('Operation completed successfully!');
    const buttonText = ref('OK');
    const onConfirm = ref(null);

    const showSuccess = (options = {}) => {
        title.value = options.title || 'Success!';
        message.value = options.message || 'Operation completed successfully!';
        buttonText.value = options.buttonText || 'OK';
        onConfirm.value = options.onConfirm || null;
        show.value = true;
    };

    const close = () => {
        show.value = false;
        // Reset to defaults after closing
        setTimeout(() => {
            title.value = 'Success!';
            message.value = 'Operation completed successfully!';
            buttonText.value = 'OK';
            onConfirm.value = null;
        }, 300);
    };

    const confirm = () => {
        if (typeof onConfirm.value === 'function') {
            onConfirm.value();
        }
        close();
    };

    return {
        show,
        title,
        message,
        buttonText,
        showSuccess,
        close,
        confirm,
    };
}
