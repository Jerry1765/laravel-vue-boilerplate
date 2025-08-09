<!-- resources/js/pages/LoginPage.vue -->
<template>
    <div class="min-h-screen flex items-center justify-center bg-base-200">
        <div class="card w-full max-w-md bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Login</h2>
                <form @submit.prevent="login">
                    <div class="form-control mb-4">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Email</legend>
                            <input type="email" class="input input-bordered w-full" placeholder=""
                                   v-model="form.email"
                                   required/>
                        </fieldset>
                    </div>
                    <div class="form-control mb-6">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Password</legend>
                            <input type="password" class="input input-bordered w-full" placeholder=""
                                   v-model="form.password"
                                   required/>
                        </fieldset>
                    </div>
                    <div class="form-control">
                        <button class="btn btn-block btn-primary" :disabled="loading">
                            <span v-if="loading" class="loading loading-spinner"></span>
                            Log in
                        </button>
                    </div>
                </form>
                <div class="divider">OR</div>
                <div class="text-center">
                    <p class="text-sm">
                        Don't have an account?
                        <router-link to="/register" class="link link-primary">Create one</router-link>
                    </p>
                </div>
                <div class="text-sm text-center mt-2">
                    <router-link to="/forgot-password" class="link link-hover link-primary">Forgot password?
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {useAuthStore} from '@/stores/auth';
import {useRouter} from 'vue-router';
import 'notyf/notyf.min.css';
import {showErrorToast} from "../constants/toast.js";

const authStore = useAuthStore();
const router = useRouter();
const loading = ref(false);
const form = ref({
    email: '',
    password: ''
});

const login = async () => {
    loading.value = true;
    try {
        const result = await authStore.login(form.value);
        if (result?.two_factor_required) {
            router.push({name: 'login.2fa'});
        } else if (result?.verify_email) {
            router.push({name: 'verification.notice', params: {email: form.value.email}});
        } else {
            router.push('/admin');
        }
    } catch (error) {
        showErrorToast(error.message ?? 'Error logging in.');
    } finally {
        loading.value = false;
    }
};
</script>
