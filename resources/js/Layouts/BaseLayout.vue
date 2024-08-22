<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    title: String
})

</script>

<template>
    <div>
        <Head :title="title" />

        <header v-if="$slots.header">
            <slot name="header"></slot>
        </header>

        <main class="flex w-full min-h-screen bg-slate-100 p-8">
            <slot></slot>
        </main>

        <footer v-if="$slots.footer">
            <slot name="footer"></slot>
        </footer>

    </div>
</template>

<style scoped>

</style>
