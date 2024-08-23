<script setup>
import { useForm } from '@inertiajs/vue3';
import BaseLayout from "../Layouts/BaseLayout.vue";

const form = useForm({
    email: '',
    password: '',
})

const submit = () => {
    form.post(route('login.post'), {
        onFinish: () => form.reset('password'),
    });
}

</script>

<template>
    <BaseLayout title="Home | Login">

        <div class="flex w-full min-h-screen">

            <div class="m-auto flex flex-col gap-4">

                <form @submit.prevent="submit">

                    <div class="flex flex-col bg-white p-6 w-auto lg:w-96 gap-4 rounded-md shadow-sm border border-gray-200">

                        <h1 class="font-bold text-3xl lg:text-4xl text-orange-600 text-center">Iniciar Sesión</h1>

                        <!-- email -->
                        <div class="flex flex-col">
                            <label for="email">Email:</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                v-model="form.email"
                                placeholder="Ingresa tu email"
                                class="border border-black rounded-sm p-1">
                            <span
                                class="text-sm font-medium italic text-red-500"
                                v-show="form.errors.email"
                            >{{ form.errors.email }}</span>
                        </div>
                        <!-- password -->
                        <div class="flex flex-col">
                            <label for="password">Contraseña:</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                v-model="form.password"
                                placeholder="Ingresa tu contraseña"
                                class="border border-black rounded-sm p-1">
                            <span
                                class="text-sm font-medium italic text-red-500"
                                v-show="form.errors.password"
                            >{{ form.errors.password }}</span>
                        </div>

                        <button class="text-sm font-semibold bg-emerald-500 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition ease-in-out duration-300">Iniciar sesión</button>
                    </div>

                </form>
            </div>

        </div>


    </BaseLayout>
</template>

<style scoped>

</style>
