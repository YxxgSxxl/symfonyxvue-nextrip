<script setup lang="ts">
import { ref } from 'vue' // Refs
import axios from 'axios' // Axios Fetch
import TripForm from '../components/TripForm.vue' // Trip Form component

let isLoading: boolean = ref(false)
let search: boolean = ref(true)

async function submitForm() {
    // search = false
    isLoading = true
    console.log("sdzf")
}
</script>

<template>
    <div class="trip">
        <h1>Compare two cities</h1>

        <TripForm v-if="search" @search="submitForm" />
    </div>
</template>

<style lang="scss" scoped>
</style>