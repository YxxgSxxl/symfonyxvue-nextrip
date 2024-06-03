<script setup lang="ts">
import { ref } from 'vue' // Refs
import OpenWeatherMapService from '../services/openweathermap.service'; // Service
import TripForm from '../components/TripForm.vue' // Trip Form component

let isLoading: boolean = ref(false)
let search: boolean = ref(true)

// fetch('http://127.0.0.1:8000/api', {
//     mode : 'no-cors'
// })
// .then(res => {
//     console.log(res);
// })

async function submitForm(query: String) {
    search = false
    isLoading = true
    // console.log(search)
    const weatherData = await OpenWeatherMapService.getWeatherData(query.value[0], query.value[1]) // 1st fetch to take the informations for the Weather Card comp

    console.log(weatherData);
    

    // const weatherDataFull = await OpenWeatherMapService.getWeatherDataFull(weatherData.coord.lat, weatherData.coord.lon, weatherData.coord.lat, weatherData.coord.lon)

    // console.log(weatherDataFull);
}
</script>

<template>
    <div class="trip">
        <h1>Compare two <span class="blue-text">cities</span></h1>

        <TripForm v-if="search" @search="submitForm" />
    </div>
</template>

<style lang="scss" scoped>
</style>