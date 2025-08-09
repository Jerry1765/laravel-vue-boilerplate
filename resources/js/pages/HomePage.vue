<template>
    <div>
        Successfully installed
    </div>
</template>

<script setup>
import {onMounted, getCurrentInstance} from 'vue';

const loader = getCurrentInstance()?.appContext.config.globalProperties.$loader;


onMounted(async () => {
    try {
        loader?.show();
        document.title = `App Name`;
    } finally {
        loader?.hide();
    }
});
</script>
<style scoped>

</style>
