<template>
    <transition name="fade">
        <div
            v-if="isLoading"
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-base-100 transition-opacity duration-500"
        >
            <span class="loading loading-spinner loading-lg text-primary"></span>
        </div>
    </transition>
</template>

<script setup>
import { ref } from 'vue';

const isLoading = ref(false);

// Expose methods to control loading state
const show = () => isLoading.value = true;
const hide = () => isLoading.value = false;

// Expose methods for other components to use
defineExpose({ show, hide });
</script>

<style scoped>
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-leave-to {
    opacity: 0;
}
</style>
