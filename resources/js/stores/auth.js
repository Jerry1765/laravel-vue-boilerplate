import {defineStore} from 'pinia';
import http from '../http.js';
import {useRouter} from "vue-router";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token') || null,
        isAuthenticated: false,
        roles: [],
        tfaRequired: false,
        tfaUserId: null,
        tfaMethod: null,
        permissions: [],
    }),

    actions: {
        async login(credentials) {
            const response = await http.request('/api/auth/login', {
                method: 'POST',
                body: JSON.stringify(credentials),
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                if (response.status === 403) {
                    if (errorData.message === 'not-verified') {
                        return {verify_email: true};
                    }
                }
                throw new Error(errorData.message ?? 'Login failed');
            }

            const data = await response.json();

            if (data.two_factor_required) {
                this.tfaRequired = true;
                this.tfaUserId = data.user_id;
                this.tfaMethod = data.method;
                return {two_factor_required: true};
            } else {
                this.setAuth(data.user, data.access_token);
                await this.fetchUser();
            }
        },

        async verifyTfa(code) {
            const response = await http.request('/api/auth/login/2fa', {
                method: 'POST',
                body: JSON.stringify({
                    user_id: this.tfaUserId,
                    code: code,
                }),
                headers: {'Content-Type': 'application/json'}
            });

            if (!response.ok) throw new Error('2FA verification failed');

            const data = await response.json();
            this.setAuth(data.user, data.access_token);
            await this.fetchUser();
            this.clearTfa();
        },

        async register(userData) {
            const response = await http.request('/api/auth/register', {
                method: 'POST',
                body: JSON.stringify(userData),
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            //console.log(response);
            if (!response.ok) throw new Error('Registration failed');

            const {user, access_token} = await response.json();
            this.setAuth(user, access_token);
            //this.router.push({name: 'verification.notice'});
        },

        async logout() {
            try {
                // Use direct fetch to avoid interception
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.token}`
                    }
                });
            } finally {
                this.clearAuth();
            }
        },

        async fetchUser() {
            if (!this.token) return;

            try {
                const response = await http.request('/api/user', {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    const userData = await response.json();
                    this.user = {
                        id: userData.id,
                        name: userData.name,
                        email: userData.email,
                        email_verified_at: userData.email_verified_at,
                        two_factor_enabled: userData.two_factor_enabled,
                        two_factor_method: userData.two_factor_method
                    };
                    this.roles = userData.roles;
                    this.permissions = userData.permissions;
                    this.isAuthenticated = true;
                } else {
                    await this.logout();
                }
            } catch (error) {
                console.error('Failed to fetch user', error);
            }
        },

        async resendVerificationEmail(email) {
            await http.request('/api/auth/email/resend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email
                })
            });
        },

        // Helper methods
        setAuth(user, token) {
            this.user = user;
            this.token = token;
            this.isAuthenticated = true;
            localStorage.setItem('auth_token', token);
        },

        clearTfa() {
            this.tfaRequired = false;
            this.tfaUserId = null;
            this.tfaMethod = null;
        },

        clearAuth() {
            this.user = null;
            this.token = null;
            this.isAuthenticated = false;
            this.roles = [];
            this.permissions = [];
            localStorage.removeItem('auth_token');
            this.clearTfa();
        }
    },

    // Getters remain unchanged
    getters: {
        isAdmin: (state) => state.roles?.some(role => role.name === 'admin'),
        isEditor: (state) => state.roles?.some(role => role.name === 'editor'),
        hasPermission: (state) => (permission) => {
            return state.permissions.includes(permission);
        }
    }
});
