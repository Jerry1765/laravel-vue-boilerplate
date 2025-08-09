import {createApp} from 'vue';
import {createPinia} from 'pinia';
import {createRouter, createWebHistory} from 'vue-router';
import App from './App.vue';
import {useAuthStore} from "@/stores/auth.js";


const routes = [
    {path: '/', component: Example},
    {path: '/example', component: Example},
    {
        path: '/example/:id/:slug',
        component: Example,
        name: 'example'
    },
    { path: '/example', name: 'example', component: Example },
    {
        path: '/login',
        name: 'login',
        component: () => import('./pages/LoginPage.vue'),
        meta: {public: true}
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('./pages/RegisterPage.vue'),
        meta: {public: true}
    },
    {
        path: '/verify-email/:email?',
        name: 'verification.notice',
        component: () => import('./pages/VerifyEmailPage.vue'),
        meta: { public: true },
    },
    {
        path: '/verify-email-handler/email/verify/:id/:hash',
        name: 'verification.handler',
        component: () => import('./pages/EmailVerificationHandlerPage.vue'),
        meta: { public: true }
    },
    {
        path: '/not-found',
        name: 'not-found',
        component: () => import('./pages/NotFoundPage.vue')
    },
    {
        path: '/login/2fa',
        name: 'login.2fa',
        component: () => import('./pages/TwoFactorChallengePage.vue'),
        meta: { public: true } // Accessible before full authentication
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: () => import('./pages/ForgotPasswordPage.vue'),
        meta: { public: true }
    },
    {
        path: '/reset-password/:token',
        name: 'reset-password',
        component: () => import('./pages/ResetPasswordPage.vue'),
        meta: { public: true }
    },
    {
        path: '/admin',
        component: () => import('./components/layout/AdminLayout.vue'),
        meta: {requiresAuth: true, requiresVerify: true},
        children: [
            {
                path: '',
                name: 'admin-dashboard',
                component: () => import('./pages/admin/DashboardPage.vue'),
                meta: {title: 'Dashboard'}
            },
            {
                path: 'users',
                name: 'admin-users',
                component: () => import('./pages/admin/UsersPage.vue'),
                meta: {
                    title: 'User management',
                    permissions: ['manage_users']
                }
            },
            {
                path: 'profile',
                name: 'AdminMyProfilePage',
                component: () => import('./pages/admin/AdminMyProfile.vue'),
                meta: {
                    title: 'My profile'
                }
            },
            {
                path: 'settings',
                name: 'AdminSettingsPage',
                component: () => import('./pages/admin/AdminSettingsPage.vue'),
                meta: {
                    title: 'Settings',
                    permissions: ['manage_settings']
                }
            }
        ]
    },
    {
        path: '/:pathMatch(.*)*',  // Matches any route
        redirect: '/not-found'     // Redirect to not-found page
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to) {
        if (to.hash) {
            return {
                el: to.hash
            }
        }
    }
});

router.beforeEach(async (to, from, next) => {
    if (window.$vueApp && window.$vueApp.config.globalProperties.$loader) {
        window.$vueApp.config.globalProperties.$loader.show();
    }

    // Optional: Scroll to top on navigation
    window.scrollTo(0, 0);

    const authStore = useAuthStore();

    // Fetch user if token exists
    if (localStorage.getItem('auth_token') && !authStore.user) {
        await authStore.fetchUser();
    }

    // Check if route requires authentication
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        return next({name: 'login'});
    }

    if (to.meta.requiresVerify && authStore.isAuthenticated && !authStore.user.email_verified_at) {
        return next({ name: 'verification.notice' });
    }

    // Check permissions
    if (to.meta.permissions) {
        const hasPermission = to.meta.permissions.every(perm =>
            authStore.hasPermission(perm)
        );

        if (!hasPermission) {
            return next({name: 'unauthorized'});
        }
    }

    next();
});

router.afterEach(() => {
    // Hide loading curtain with a slight delay
    setTimeout(() => {
        if (window.$vueApp && window.$vueApp.config.globalProperties.$loader) {
            window.$vueApp.config.globalProperties.$loader.hide();
        }
    }, 200);
});

const app = createApp(App);
window.$vueApp = app;
app.use(createPinia());
app.use(router);
app.mount('#app');
