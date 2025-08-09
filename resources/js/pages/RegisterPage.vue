<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md p-6">
            <div class="bg-white rounded-xl shadow-xl p-8">
                <h2 class="card-title text-2xl mb-6">Create an account</h2>
                <form @submit.prevent="register">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Enter full name"
                                    class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary focus:border-secondary transition"
                                    :class="{ 'border-red-500': errors.name }"
                                >
                            </div>
                            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="Enter email address"
                                    class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary focus:border-secondary transition"
                                    :class="{ 'border-red-500': errors.email }"
                                >
                            </div>
                            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    placeholder="Enter password"
                                    class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary focus:border-secondary transition"
                                    :class="{ 'border-red-500': errors.password }"
                                >
                            </div>
                            <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    placeholder="Confirm password"
                                    class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary focus:border-secondary transition"
                                    :class="{ 'border-red-500': errors.password_confirmation }"
                                >
                            </div>
                            <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ errors.password_confirmation }}</p>
                        </div>

                        <div class="flex items-center">
                            <input
                                id="terms"
                                v-model="form.terms"
                                type="checkbox"
                                class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded"
                            >
                            <label for="terms" class="ml-2 block text-sm text-gray-700">
                                I agree with <a href="#" class="text-primary hover:underline"> terms</a>
                            </label>
                        </div>
                        <p v-if="errors.terms" class="text-sm text-red-600">{{ errors.terms }}</p>

                        <div class="pt-2">
                            <button
                                type="submit"
                                class="btn btn-block btn-primary"
                                :disabled="loading"
                                :class="{ 'opacity-75 cursor-not-allowed': loading }"
                            >
                <span v-if="loading" class="mr-2">
                  <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
                                Create account
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <router-link to="/login" class="text-primary font-medium hover:text-primary hover:underline">
                            Log in
                        </router-link>
                    </p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-sm text-white opacity-80">
                    &copy; {{ new Date().getFullYear() }} Example.com. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import 'notyf/notyf.min.css';
import {showErrorToast} from "../constants/toast.js";

const router = useRouter();
const authStore = useAuthStore();

const loading = ref(false);
const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false
});

const errors = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: ''
});

const validateForm = () => {
    let isValid = true;
    errors.value = { name: '', email: '', password: '', password_confirmation: '', terms: '' };

    // Name validation
    if (!form.value.name.trim()) {
        errors.value.name = 'Name is required';
        isValid = false;
    } else if (form.value.name.length < 3) {
        errors.value.name = 'Name must be at least 3 characters';
        isValid = false;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!form.value.email) {
        errors.value.email = 'Email is required';
        isValid = false;
    } else if (!emailRegex.test(form.value.email)) {
        errors.value.email = 'Please, enter a valid email';
        isValid = false;
    }

    // Password validation
    if (!form.value.password) {
        errors.value.password = 'Password is required';
        isValid = false;
    } else if (form.value.password.length < 8) {
        errors.value.password = 'Password must be at least 8 characters';
        isValid = false;
    } else if (!/[A-Z]/.test(form.value.password)) {
        errors.value.password = 'Password must contain at least one uppercase letter';
        isValid = false;
    } else if (!/[0-9]/.test(form.value.password)) {
        errors.value.password = 'Password must contain at least one number';
        isValid = false;
    }

    // Password confirmation
    if (form.value.password !== form.value.password_confirmation) {
        errors.value.password_confirmation = 'Passwords do not match';
        isValid = false;
    }

    // Terms agreement
    if (!form.value.terms) {
        errors.value.terms = 'You must agree to the terms';
        isValid = false;
    }

    return isValid;
};

const register = async () => {
    if (!validateForm()) return;

    loading.value = true;

    try {
        await authStore.register({
            name: form.value.name,
            email: form.value.email,
            password: form.value.password,
            password_confirmation: form.value.password_confirmation
        });
        router.push({name: 'verification.notice'});
    } catch (error) {
        // Handle API errors
        if (error.response && error.response.data.errors) {
            const apiErrors = error.response.data.errors;

            if (apiErrors.email) {
                errors.value.email = apiErrors.email[0];
            }

            if (apiErrors.name) {
                errors.value.name = apiErrors.name[0];
            }

            if (apiErrors.password) {
                errors.value.password = apiErrors.password[0];
            }
        } else {
            showErrorToast('Registration failed.');
        }
    } finally {
        loading.value = false;
    }
};
</script>
