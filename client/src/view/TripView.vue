<script setup lang="ts">
import { ref } from 'vue' // Refs
import axios from 'axios' // Axios
import ConfettiExplosion from "vue-confetti-explosion" // Vue Conffeti Animations lib
import TripForm from '../components/TripForm.vue' // Trip Form component
import TripCard from '../components/TripCard.vue' // Trip Card component
import WinnerCard from '../components/WinnerCard.vue' // Win Card component

let title = ref("Compare two cities") // H1 tag value
let isLoading: any = ref(false) // Loading bool
let search: any = ref(true) // Search bool
let searched: any = ref(false) // Search done bool
let error: any = ref("") // Error message value
let data_weather: any = ref([]); // Weather data Array


async function submitForm(query: String) {
    search.value = false
    isLoading.value = true
    
    await axios.get(`http://127.0.0.1:8000/api/${query.value[0].value}/${query.value[1].value}`, {
    })
    .then(res => {
        return res
    })
    .then(json => {
        isLoading.value = false
        searched.value = true

        data_weather.value = json
        // json = data_weather.value
        // console.log(json.data[0]);
        // console.log(data_weather.value.data[0]);
        title.value = "Results"
        return data_weather
    })
    // Catches Axios errors (like blank query)
    .catch(err => {
        error.value = err
    })
}
</script>

<template>
    <div class="trip">
        <h1>{{ title }}</h1>
        
        <TripForm v-if="search" @search="submitForm" />
        
        <div class="weather-error">
            {{ error }}
        </div>
            
        <div class="trip-debug" v-if="searched">
            <!-- {{ data_weather?.data[0]?.winner }} -->
            <!-- {{ data_weather?.data[1] }} -->
        </div>

        <div class="trip-winwrapper" v-if="searched">
            <ConfettiExplosion class="trip-conffeti" :particleCount="100" :particleSize="7" :duration="3000" />
            <WinnerCard :winnerData="data_weather.data[1]" />
        </div>

        <div class="trip-cwrapper" v-if="searched">
            <TripCard v-for="(data, i) in data_weather.data[0].cities" :key="i" :weatherData="data" />
        </div>

    </div>
</template>

<style lang="scss" scoped>
.trip {
    padding: 0 2rem 0 2rem;
    align-items: center;

    .weather-error {
        color: red;
        text-align: center;
    }

    &-cwrapper {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    &-winwrapper {
        align-items: center;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;

        .trip-conffeti {
            position: absolute;
            top: 10px;
        }
    }
}
</style>