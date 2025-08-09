<template>
    <div class="min-h-screen flex items-center justify-center bg-base-200">
        <div class="card w-full max-w-md bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Verify your email</h2>
                <p class="mb-4">Thank you for registering! Before you continue, please verify your email
                    address by clicking on the link we just sent you. If you did not receive the email, we will be happy to send you
                    another one.</p>
                <div v-if="verificationSent" class="alert alert-success mb-4">
                    New verification link sent.
                </div>
                <button @click="resendVerification" class="btn btn-primary" :disabled="sending">
                    <span v-if="sending" class="loading loading-spinner"></span>
                    Send verification email again
                </button>
                <div class="justify-end card-actions">
                    <button class="btn btn-ghost" @click="logout">Log out</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {useAuthStore} from '@/stores/auth';
import {useRouter} from 'vue-router';
import {useRoute} from 'vue-router';
import 'notyf/notyf.min.css';
import {showErrorToast} from "../constants/toast.js";

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();
const sending = ref(false);
const verificationSent = ref(false);

const logout = async () => {
    await authStore.logout();
    router.push('/login');
};

const resendVerification = async () => {
    const emailFromParams = route.params.email;
    //console.log(emailFromParams);

    if (!emailFromParams) {
        return;
    }

    sending.value = true;
    verificationSent.value = false;
    try {
        await authStore.resendVerificationEmail(emailFromParams);
        verificationSent.value = true;
    } catch (error) {
        showErrorToast('Could not send verification email.');
    } finally {
        sending.value = false;
    }
};
</script>
