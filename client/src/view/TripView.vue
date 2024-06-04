<script setup lang="ts">
import { ref } from 'vue' // Refs
import axios from 'axios'
import TripForm from '../components/TripForm.vue' // Trip Form component

let isLoading: any = ref(false)
let search: any = ref(true)
let error: any = ref("")
// let isError: any = ref(false)
let data_weather: any = ref([]);

let message: any = ref({})

async function submitForm(query: String) {
    search.value = false
    isLoading.value = true
    
    
    await axios.get(`http://127.0.0.1:8000/api/${query.value[0].value}/${query.value[1].value}`, {
        // headers: {
        //     "Content-Type": "application/json",
        //     'Access-Control-Allow-Origin': '*',
        // }
    })
    .then(res => {
        // console.log("ici", res.json());
        return res
    })
    .then(json => {
        console.log(json);
        message.value = json
        isLoading.value = false

        data_weather.value = json
        return data_weather
    })
    // Catches Axios errors (like blank query)
    .catch(err => {
        // isError.value = true
        error.value = err
    })        
}
</script>

<template>
    <div class="trip">
        <h1>Compare two <span class="blue-text">cities</span></h1>

        <TripForm v-if="search" @search="submitForm" />

        {{ message?.data }}

        <span style="color: yellowgreen;">{{ data_weather?.data?.city2 }}</span>

        <div class="weather-error">
            {{ error }}
        </div>
    </div>
</template>

<style lang="scss" scoped>
.weather-error {
    color: red;
    text-align: center;
}
</style>