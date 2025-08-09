// resources/js/http.js
import { useAuthStore } from '@/stores/auth';

export default {
    async request(url, options = {}) {
        const authStore = useAuthStore();
        const headers = {
            ...options.headers,
        };

        // Add auth token if available
        if (authStore.token) {
            headers.Authorization = `Bearer ${authStore.token}`;
        }

        const response = await fetch(url, {
            ...options,
            headers,
        });

        // Handle 401 Unauthorized responses
        if (response.status === 401 && authStore.isAuthenticated) {
            await authStore.logout();
            throw new Error('Session expired. Please log in again.');
        }

        return response;
    },
};
