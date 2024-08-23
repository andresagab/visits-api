<script setup>
import BaseLayout from "../../Layouts/BaseLayout.vue";
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import {onMounted, ref} from "vue";
import {router} from "@inertiajs/vue3";
import axios from 'axios';

// crear referencia para el elemento del mapa
const map_element = ref(null)
// crear referencia para el mapa
const map = ref(null)
// crear referencia para las visitas
const visits = ref([])

// crear referencias para la paginación
const currentPage = ref(1)
const totalPages = ref(1)

// crear referencia de estado de carga
const loading = ref(false)

/**
 * Cargar las visitas desde la API
 * @returns {Promise<void>}
 */
const fetchData = async (page = 1) => {
    loading.value = true
    try {
        const response = await axios.get('/api/v1/visits', {
            params: { page }
        })
        visits.value = response.data
        currentPage.value = response.data.meta.current_page
        totalPages.value = response.data.meta.last_page
        generateMarkers()
    } catch (error) {
        console.log("Error al obtener las visitas:", error)
    } finally {
        loading.value = false
    }

}

/**
 * Generar los marcadores de las visitas
 */
const generateMarkers = () => {
    clearMarkers()
    visits.value.data.forEach((visit) => {
        let marker = L.marker([visit.latitude, visit.longitude]).addTo(map.value)
        marker.bindPopup("" +
            "<b>" + visit.name + "</b><br>"
            + visit.email
        )
    })
    setMapCenter(visits.value.data[0])
}

/**
 * Limpiar los marcadores
 */
const clearMarkers = () => {
    map.value.eachLayer((layer) => {
        if (layer instanceof L.Marker) {
            map.value.removeLayer(layer)
        }
    })
}

/**
 * Cambiar el foco central del mapa
 * @param visit
 */
const setMapCenter = (visit) => {
    map.value.panTo([visit.latitude, visit.longitude])
}

/**
 * Generar el mapa
 */
const generateMap = () => {
    map_element.value = document.getElementById('map')
    map.value = L.map(map_element.value).setView([51.505, -0.09], 13)
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map.value)
}

const changePage = (next = true) => {
    let newPage = next ? currentPage.value + 1 : currentPage.value - 1
    if (newPage <= totalPages.value && newPage > 0)
        fetchData(newPage)
}

/**
 * Cerrar sesión
 */
const logout = () => {
    router.post(route('logout'));
}

onMounted(() => {

    fetchData()
    generateMap()

})

</script>

<template>
    <BaseLayout title="Visitas">

        <template #header>
            <div class="inline-flex items-center justify-between w-full p-6 shadow">
                <h2 class="font-semibold text-xl text-orange-600 leading-tight">
                    Visualizador de visitas
                </h2>
                <form @submit.prevent="logout">
                    <button
                        type="submit"
                        class="px-3 py-1 rounded-md hover:bg-gray-100 text-gray-700 transition ease-in-out duration-300"
                        title="Click para cerrar sesión"
                    >Cerrar sesión</button>
                </form>
            </div>
        </template>

        <div class="w-full">

            <div class="flex flex-row gap-6 w-full">
                <!-- data list -->
                <div class="col-span-1 min-w-20 bg-white rounded-md shadow-sm hover:shadow-md p-4 transition ease-in-out duration-300">

                    <h3 class="text-xl font-bold text-indigo-500 mb-1">Visitas registradas</h3>

                    <p class="text-sm font-normal text-pretty break-words text-gray-800 mb-3">Haz click en una visita para ver la ubicación:</p>

                    <ul class="flex flex-col gap-1 w-full">
                        <li
                            v-for="visit in visits.data"
                            @click="setMapCenter(visit)"
                            title="Click para ver en el mapa"
                            class="border-b border-gray-300 hover:border-y"
                        >
                            <article class="block hover:bg-gray-100 hover:shadow-sm transition ease-in-out duration-300 p-2 rounded-sm cursor-pointer">
                                <header>
                                    <h3 class="font-bold text-md text-pretty break-words">{{ visit.name }}</h3>
                                </header>
                                <footer>
                                    <p class="font-normal text-sm text-pretty break-words text-gray-700">{{ visit.created_at }}</p>
                                </footer>
                            </article>
                        </li>
                    </ul>

                    <div class="flex flex-col items-center mt-4">
                        <div class="inline-flex items-center">
                            <button
                                :disabled="currentPage <= 1 || loading"
                                @click="changePage(false)"
                                class="bg-blue-500 text-white text-sm py-1 px-2 rounded-md"
                            >
                                Anterior
                            </button>
                            <p
                                v-show="visits.meta"
                                class="mx-2 text-pretty text-sm text-gray-800 text-center"
                            >
                                Página <span class="font-bold">{{ currentPage }}</span> de <span class="font-bold">{{ totalPages }}</span>
                            </p>
                            <button
                                :disabled="currentPage >= totalPages || loading"
                                @click="changePage()"
                                class="bg-blue-500 text-white text-sm py-1 px-2 rounded-md"
                            >
                                Siguiente
                            </button>
                        </div>
                    </div>

                </div>
                <!-- map -->
                <div class="col-span-1 w-full min-h-screen bg-white rounded-md shadow-sm hover:shadow-md p-4 transition ease-in-out duration-300">
                    <div id="map">
                    </div>
                </div>
            </div>

        </div>

    </BaseLayout>
</template>

<style scoped>
    #map {
        height: 100%;
    }
</style>
