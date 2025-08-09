<!-- resources/js/pages/admin/DashboardPage.vue -->
<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Admin dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title text-primary">Example</h2>
                    <p class="text-4xl font-bold">{{ statistics?.example }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import {useAuthStore} from '@/stores/auth';
import http from "../../http.js";
import 'notyf/notyf.min.css';
import {showErrorToast} from "../../constants/toast.js";

const authStore = useAuthStore();
const statistics = ref({});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('sk-SK');
};

const fetchStatistics = async () => {
    try {
        const response = await http.request('/api/admin/statistics');

        if (response.ok) {
            statistics.value = await response.json();
        }
    } catch (error) {
        showErrorToast('Error fetching statistics.');
        console.error('Failed to fetch stats', error);
    }
};

onMounted(() => {
    fetchStatistics();
});
</script>
