<script setup lang="ts">
import { ref } from 'vue' // Refs
import TripForm from '../components/TripForm.vue' // Trip Form component

let isLoading: boolean = ref(false)
let search: boolean = ref(true)

function submitForm(city1: string, city2: string) {
    
}
</script>

<template>
    <div class="trip">
        <h1>Compare two cities</h1>

        <TripForm v-if="search" />
    </div>
</template>

<style lang="scss" scoped>
</style>