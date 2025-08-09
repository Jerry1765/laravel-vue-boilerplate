<!-- resources/js/layouts/AdminLayout.vue -->
<template>
    <div class="flex h-screen bg-base-100">
        <!-- Sidebar -->
        <div :class="[
            'bg-base-300 text-base-content transition-transform duration-300 transform',
            'fixed lg:static lg:translate-x-0 z-50 h-full w-64 ',
            sidebarOpen ? 'translate-x-0': '-translate-x-full lg:translate-x-0'
        ]">
            <div class="p-4">
                <h1 class="text-xl font-bold">{{ authStore.user.name }}</h1>
                <p class="text-sm opacity-70">{{ authStore.user.email }}</p>
            </div>
            <ul class="menu p-4">
                <li>
                    <router-link to="/admin" class="flex items-center">
                        <ChartBarIcon class="w-5 h-5"/>
                        Dashboard
                    </router-link>
                </li>
                <li v-if="authStore.hasPermission('manage_content')">
                    <router-link to="/admin/content" class="flex items-center">
                        <CalendarIcon class="w-5 h-5"/>
                        Content
                    </router-link>
                </li>
            </ul>
        </div>

        <div
            v-if="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-gray-900 opacity-50 lg:hidden"
        ></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- Header -->
            <header class="bg-base-100 border-b border-base-300">
                <div class="flex justify-between items-center p-4">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="lg:hidden btn btn-outline btn-square btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h2 class="text-lg font-semibold">{{ $route.meta.title || 'Admin' }}</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" class="avatar placeholder">
                                <div class="bg-neutral text-neutral-content rounded-full w-10">
                                    <span>{{ userInitials }}</span>
                                </div>
                            </div>
                            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                <li>
                                    <router-link to="/admin/profile">My profile</router-link>
                                </li>
                                <li><a @click="logout">Log out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-3 bg-base-200">
                <router-view @close-sidebar="sidebarOpen = false"></router-view>
            </main>
        </div>
    </div>
</template>

<script setup>
import {computed, ref, watch} from 'vue';
import {
    ChartBarIcon,
    CalendarIcon,
} from '@heroicons/vue/24/outline';
import {useAuthStore} from '@/stores/auth';
import {useRouter} from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();
const sidebarOpen = ref(false);

watch(() => router.currentRoute.value, () => {
    sidebarOpen.value = false;
});

document.title = `Admin panel`;

const userInitials = computed(() => {
    if (!authStore.user?.name) return '?';
    const names = authStore.user.name.split(' ');
    return names.map(n => n[0]).join('');
});

const logout = async () => {
    await authStore.logout();
    router.push('/login');
};
</script>
