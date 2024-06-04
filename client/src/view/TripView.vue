<script setup lang="ts">
import { ref } from 'vue' // Refs
// import OpenWeatherMapService from '../services/openweathermap.service'; // Service
import axios from 'axios'
import TripForm from '../components/TripForm.vue' // Trip Form component

let isLoading: any = ref(false)
let search: any = ref(true)
let error: any = ref("")
// let isError: any = ref(false)
let data_weather: any = ref([]);

let message: any = ref({})

async function submitForm(query: String) {
    data_weather.value = [];
    search.value = false
    isLoading.value = true
    
    await axios.get(`http://127.0.0.1:8000/api/${query.value[0].value}/${query.value[1].value}`, {
        headers: { 
            'Content-Type': 'application/json',
            mode: 'no-cors',
            dataType: 'jsonp',
         }
    })
    .then(res => {
        // console.log("ici", res.json());
        return res
    })
    .then(json => {
        // if(isError.value = true) {
        //     null
        // } else {
        //     console.log(json);
        //     message.value = json
        //     return json
        // }

        console.log(json);
        message.value = json
        isLoading.value = false
        return json
    })
    // Catches Axios errors (like blank query)
    .catch(err => {
        // isError.value = true
        error.value = err
    })    

    // let weatherData = await axios.all([
    //         axios.post(`https://api.openweathermap.org/data/2.5/weather?q=${query.value[0].value}&units=metric&appid=95542917d76459372397547a96610cd8`),
    //         axios.post(`https://api.openweathermap.org/data/2.5/weather?q=${query.value[1].value}&units=metric&appid=95542917d76459372397547a96610cd8`),
    //     ])
    // .then(res => {
    //     // console.log(res.data);
    //     return {
    //         city1: res[0],
    //         city2: res[1]
    //     }
    // })
    // .catch(error => {
    //     // console.log(error);
    //     if(error.name) {
    //         console.log(error.message)
            
    //         // error = error
    //     }
    // })
    
    // console.log(weatherData);
    // console.log(weatherData.city1);
    
    // let weatherDataFull = await axios.all([
    //     axios.post()
    // ])


    // const weatherData = await OpenWeatherMapService.getWeatherData(query.value[0]) // 1st fetch to take the informations for the Weather Card comp

    // data_weather.value.push(weatherData)
    // console.log(data_weather);
    
    // console.log(weatherData);
    

    // data_weather = weatherData

    // data_weather.value.push(weatherData);

    // console.log(data_weather);
    
    
    
    // if(weatherData.error) {
    //     error = "eifjiefjej"
    // }

    // if()
    // if(weatherData.name == "AxiosError") {
    //     alert('sijfzifsjij')
    // }

    // console.log(weatherData);
    
}
</script>

<template>
    <div class="trip">
        <h1>Compare two <span class="blue-text">cities</span></h1>

        <TripForm v-if="search" @search="submitForm" />

        {{ message?.data }}

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