<script setup lang="ts">
import { ref } from 'vue' // Refs
import axios from 'axios' // Axios
import TripForm from '../components/TripForm.vue' // Trip Form component
import TripCard from '../components/TripCard.vue' // Trip Card component

let title = ref("Compare two cities")
let isLoading: any = ref(false)
let search: any = ref(true)
let searched: any = ref(false)
let error: any = ref("")
let data_weather: any = ref([]);


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

        <div class="trip-cwrapper">
            <TripCard v-if="searched" v-for="(data, i) in data_weather.data[0]" :key="i" :weatherData="data" />
        </div>

        <div class="weather-error">
            {{ error }}
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
}
</style>