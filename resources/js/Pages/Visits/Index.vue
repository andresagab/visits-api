<script setup>

import BaseLayout from "../../Layouts/BaseLayout.vue";
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import {onMounted, ref} from "vue";

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

</script>

<template>
    <BaseLayout title="Visits">

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
