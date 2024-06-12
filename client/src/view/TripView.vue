<script setup lang="ts">
import { ref } from 'vue' // Refs
import axios from 'axios' // Axios
import ConfettiExplosion from "vue-confetti-explosion" // Vue Conffeti Animations lib
import TripForm from '../components/TripForm.vue' // Trip Form component
import WinnerCard from '../components/WinnerCard.vue' // Win Card component
import TripCard from '../components/TripCard.vue' // Trip Card component

let title = ref("Research two cities") // H1 tag value
let isLoading: any = ref(false) // Loading bool
let search: any = ref(true) // Search bool
let searched: any = ref(false) // Search done bool
let error: any = ref("") // Error message value
let isError: any = ref(false);
let data_weather: any = ref([]); // Weather data Array


async function submitForm(query: any) {
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

      // ERROR HANDLING --> If the cities entered are the same
      if (query.value[0].value.toLowerCase() == query.value[1].value.toLowerCase()) {
        isError.value = true
        isLoading.value = false
        searched.value = false
        error.value = "Please, don't insert the same city name twice"
      }

        data_weather.value = json
        title.value = "Results"
        return data_weather
    })
    // Catches Axios errors (like blank query)
    .catch(err => {
        isError.value = true
        isLoading.value = false
        error.value = err

        // More informations about the error (Debugging)
        console.log(err.toJSON());
        
    })
}
</script>

<template>
    <div class="trip">
        <h1>{{ title }}</h1>
        
        <TripForm v-if="search" @search="submitForm" />

        <div class="trip-loading" v-if="isLoading">
            <!-- Loading icon from https://loading.io/css/ -->
            <div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>
        
        <div class="weather-error" v-if="isError">
            {{ error }}
            <router-link to="/">Homepage</router-link>
        </div>

        <div class="trip-winwrapper" v-if="searched">
            <ConfettiExplosion class="trip-conffeti" :particleCount="80" :particleSize="7" :duration="2400" />
            <WinnerCard :weatherData="data_weather.data" />
        </div>

        <div class="trip-cwrapper" v-if="searched">
            <TripCard v-for="(data, i) in data_weather.data.citiestoday" :key="i" :weatherData="data" />
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

        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .trip-loading {
        margin-top: 6rem;
        color: #006fb9;
        text-align: center;
        align-items: center;

        // LOADING ICON from https://loading.io/css/
        .lds-default,
        .lds-default div {
          box-sizing: border-box;
        }
        .lds-default {
          display: inline-block;
          position: relative;
          width: 80px;
          height: 80px;
        }
        .lds-default div {
          position: absolute;
          width: 6.4px;
          height: 6.4px;
          background: currentColor;
          border-radius: 50%;
          animation: lds-default 1.2s linear infinite;
        }
        .lds-default div:nth-child(1) {
          animation-delay: 0s;
          top: 36.8px;
          left: 66.24px;
        }
        .lds-default div:nth-child(2) {
          animation-delay: -0.1s;
          top: 22.08px;
          left: 62.29579px;
        }
        .lds-default div:nth-child(3) {
          animation-delay: -0.2s;
          top: 11.30421px;
          left: 51.52px;
        }
        .lds-default div:nth-child(4) {
          animation-delay: -0.3s;
          top: 7.36px;
          left: 36.8px;
        }
        .lds-default div:nth-child(5) {
          animation-delay: -0.4s;
          top: 11.30421px;
          left: 22.08px;
        }
        .lds-default div:nth-child(6) {
          animation-delay: -0.5s;
          top: 22.08px;
          left: 11.30421px;
        }
        .lds-default div:nth-child(7) {
          animation-delay: -0.6s;
          top: 36.8px;
          left: 7.36px;
        }
        .lds-default div:nth-child(8) {
          animation-delay: -0.7s;
          top: 51.52px;
          left: 11.30421px;
        }
        .lds-default div:nth-child(9) {
          animation-delay: -0.8s;
          top: 62.29579px;
          left: 22.08px;
        }
        .lds-default div:nth-child(10) {
          animation-delay: -0.9s;
          top: 66.24px;
          left: 36.8px;
        }
        .lds-default div:nth-child(11) {
          animation-delay: -1s;
          top: 62.29579px;
          left: 51.52px;
        }
        .lds-default div:nth-child(12) {
          animation-delay: -1.1s;
          top: 51.52px;
          left: 62.29579px;
        }
        @keyframes lds-default {
          0%, 20%, 80%, 100% {
            transform: scale(1);
          }
          50% {
            transform: scale(1.5);
          }
        }
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