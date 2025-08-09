<template>
    <div class="min-h-screen flex items-center justify-center bg-base-200">
        <div class="card w-full max-w-md bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-2">Forgot your password?</h2>
                <p class="text-sm text-base-content/70 mb-6">No problem. Just let us know your email address and we will email you a password reset link.</p>

                <div v-if="status" class="alert alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ status }}</span>
                </div>

                <form @submit.prevent="handleForgotPassword">
                    <div class="form-control mb-4">
                        <label for="email" class="label">
                            <span class="label-text">Email</span>
                        </label>
                        <input type="email" id="email" v-model="email" class="input input-bordered w-full" :class="{'input-error': errors.email}" required autofocus />
                        <label class="label" v-if="errors.email">
                            <span class="label-text-alt text-error">{{ errors.email[0] }}</span>
                        </label>
                    </div>
                    <div class="form-control">
                        <button class="btn btn-block btn-primary" :disabled="loading">
                            <span v-if="loading" class="loading loading-spinner"></span>
                            Send link to reset password
                        </button>
                    </div>
                </form>
                <div class="text-center mt-4">
                    <router-link to="/login" class="link link-primary text-sm">Back to login</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import http from '@/http';
import { showErrorToast, showSuccessToast } from '@/constants/toast';

const email = ref('');
const loading = ref(false);
const errors = ref({});
const status = ref('');

const handleForgotPassword = async () => {
    loading.value = true;
    errors.value = {};
    status.value = '';

    try {
        const response = await http.request('/api/auth/forgot-password', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ email: email.value }),
        });
        const data = await response.json();

        if (!response.ok) {
            if (response.status === 422) {
                errors.value = data.errors;
            } else {
                showErrorToast(data.message || 'Error sending password reset link.');
            }
        } else {
            status.value = data.message;
            showSuccessToast(data.message);
        }
    } catch (err) {
        showErrorToast('Unexpected error.');
    } finally {
        loading.value = false;
    }
};
</script>
