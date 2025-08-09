<template>
    <div class="min-h-screen flex items-center justify-center bg-base-200 p-4">
        <div class="card w-full max-w-lg bg-base-100 shadow-xl">
            <div class="card-body items-center text-center">
                <h2 class="card-title text-2xl mb-4">Email verification</h2>

                <div v-if="loading" class="py-8">
                    <span class="loading loading-spinner loading-lg text-primary"></span>
                    <p class="mt-4">Verifying your email...</p>
                </div>

                <div v-if="successMessage" class="py-8">
                    <div class="text-green-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-lg">{{ successMessage }}</p>
                    <p class="mt-2">You can now log in.</p>
                    <router-link to="/login" class="btn btn-primary mt-6">Go to login</router-link>
                </div>

                <div v-if="errorMessage" class="py-8">
                    <div class="text-red-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-lg text-error">{{ errorMessage }}</p>
                    <p class="mt-2">Try to resend the verification email, or contact your administrator.</p>
                    <router-link to="/login" class="btn btn-secondary mt-6">Back to login</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import {useRoute} from 'vue-router';
import http from '@/http.js';

const route = useRoute();
const loading = ref(true);
const successMessage = ref('');
const errorMessage = ref('');

onMounted(async () => {
    const {id, hash} = route.params;
    const {expires, signature} = route.query;

    if (!id || !hash || !expires || !signature) {
        errorMessage.value = 'Invalid or incomplete verification link.';
        loading.value = false;
        return;
    }

    try {
        const verificationUrl = `/api/auth/email/verify/${id}/${hash}?expires=${expires}&signature=${signature}`;
        const response = await http.request(verificationUrl);

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Verification failed');
        }

        successMessage.value = data.message || 'Your email has been successfully verified!';

    } catch (error) {
        errorMessage.value = 'Verification link is invalid.';
        console.error('Verification failed:', error);
    } finally {
        loading.value = false;
    }
});
</script>
