<script setup>
import BaseLayout from "../../Layouts/BaseLayout.vue";
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import {onMounted, ref} from "vue";
import {router} from "@inertiajs/vue3";

const map_element = ref(null)

onMounted(() => {

    map_element.value = document.getElementById('map')
    const map = L.map(map_element.value).setView([51.505, -0.09], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    let marker = L.marker([51.5, -0.09]).addTo(map);
    marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();

})

const logout = () => {
    router.post(route('logout'));
}

</script>

<template>
    <BaseLayout title="Visits">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Visits
            </h2>
            <form @submit.prevent="logout">
                <button type="submit">Cerrar sesión</button>
            </form>
        </template>

        <div class="w-full">
            Visits page

            <div id="map">

            </div>
        </div>


    </BaseLayout>
</template>

<style scoped>
    #map {
        height: 100%;
    }
</style>
