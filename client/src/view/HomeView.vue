<script setup lang="ts">
import BlueBtn from '../components/BlueButton.vue'
</script>

<template>
    <div class="home">
        <h1>Welcome to Nex<span class="blue-text">Trip</span>!</h1>

        <div class="home-line">
            <p class="home--p1">Do you want to travel next days?</p>
            <p class="home--p2">You can <span class="blue-text">compare</span> two detinations</p>
            <BlueBtn name="Compare" icon="/" />
        </div>


    </div>
</template>

<style lang="scss">
h1 {
    font-size: 1.5em;
    text-align: center;
    margin-bottom: 2rem;
}

.home {
    padding: 0rem 1.5rem 0rem 1.5rem;

    a {
        color: black;
        text-decoration: none;
    }

    &--p1 {
        margin-bottom: 2rem;
    }

    &--p2 {
        margin-bottom: 0.6rem;
    }
}
</style>
