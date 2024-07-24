<script setup>
import { defineAsyncComponent } from "vue";

const Loading = defineAsyncComponent(() =>
    import("@/components/inkia/Loading.vue")
);
const NotifBar = defineAsyncComponent(() =>
    import("@/components/inkia/Notifview.vue")
);
</script>
<template>
    <RouterView />
    <Loading />
    <NotifBar />
</template>
<style>
::-webkit-scrollbar {
    @apply w-2 h-2 lg:w-2;
}

::-webkit-scrollbar-thumb {
    @apply bg-slate-600/20 rounded-full hover:bg-primary/50 ease-in-out duration-100;
}
</style>
