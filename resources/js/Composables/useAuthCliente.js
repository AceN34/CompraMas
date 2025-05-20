import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export default function useAuthCliente() {
    const page = usePage();
    const isAuthenticated = computed(() => page.props.auth?.isAuthenticated ?? false);
    const cliente = computed(() => page.props.auth?.cliente ?? null);

    return {
        isAuthenticated,
        cliente,
    };
}

