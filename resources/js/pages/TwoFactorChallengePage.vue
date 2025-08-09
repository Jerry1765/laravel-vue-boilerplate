<template>
    <div class="min-h-screen flex items-center justify-center bg-base-200">
        <div class="card w-full max-w-md bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-2">Two-factor authentication</h2>
                <p class="mb-6 text-sm">
                    Enter the verification code sent to
                    <strong v-if="authStore.tfaMethod === 'app'">your authentication app</strong>
                    <strong v-if="authStore.tfaMethod === 'email'">your email address</strong>.
                </p>
                <form @submit.prevent="verifyCode">
                    <div class="form-control mb-4">
                        <input type="text" v-model="code"
                               class="input input-bordered w-full text-center tracking-[0.5em]" placeholder="123456"
                               required maxlength="6"/>
                    </div>
                    <div class="form-control">
                        <button class="btn btn-block btn-primary" :disabled="loading">
                            <span v-if="loading" class="loading loading-spinner"></span>
                            Verify
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {useAuthStore} from '@/stores/auth';
import {useRouter} from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();
const code = ref('');
const loading = ref(false);

const verifyCode = async () => {
    loading.value = true;
    try {
        await authStore.verifyTfa(code.value);
        router.push('/admin');
    } catch (error) {
        alert('Invalid code.');
    } finally {
        loading.value = false;
    }
};
</script>
