<template>
    <div class="min-h-screen bg-base-100 flex items-center justify-center p-2">
        <div class="max-w-3xl w-full">
            <div class="card bg-base-100 shadow-xl overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <!-- Illustration Section -->
                    <div
                        class="md:w-2/5 bg-primary flex items-center justify-center p-8">
                        <div class="text-center text-base-100">
                            <div class="text-9xl font-bold mb-4">404</div>
                            <div class="text-2xl font-medium">Page not found</div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="md:w-3/5 p-10">
                        <div class="mb-8">
                            <h1 class="text-4xl font-bold text-primary mb-4">Ups! Something is missing here...</h1>
                            <p class="text-lg mb-6">Seems like the page you're looking for is missing.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <router-link to="/" class="btn btn-primary btn-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Return home
                            </router-link>

                            <button @click="goBack" class="btn btn-outline btn-secondary btn-block text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Go back
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useRouter} from 'vue-router';

const router = useRouter();

const goBack = () => {
    router.go(-1);
};
</script>

<style scoped>
.card {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
}

.bg-gradient-to-br {
    background-image: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
}

@media (max-width: 768px) {
    .card {
        margin-top: 2rem;
    }
}
</style>
