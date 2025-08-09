<template>
    <GlobalLoadingCurtain ref="globalLoader" />
    <div class="min-h-screen flex flex-col">
        <Header />
        <main class="flex-grow">
            <router-view />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import Header from './components/layout/Header.vue';
import Footer from './components/layout/Footer.vue';
import {getCurrentInstance, ref} from "vue";
import GlobalLoadingCurtain from "@/components/ui/GlobalLoadingCurtain.vue";

const globalLoader = ref(null);

// Provide loader methods to all components
const showLoader = () => globalLoader.value?.show();
const hideLoader = () => globalLoader.value?.hide();

// Make loader accessible via app instance
const app = getCurrentInstance();
app.appContext.config.globalProperties.$loader = { show: showLoader, hide: hideLoader };

</script>
