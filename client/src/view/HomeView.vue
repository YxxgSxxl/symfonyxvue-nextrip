<script setup lang="ts">
import { ref } from 'vue'
import BlueBtn from '../components/BlueButton.vue'

let welcomeMsg: any = ref("")
let randNum: number = Math.floor(Math.random() * 7)

// Random h1 value
switch (randNum) {
    case 0:
        welcomeMsg = "Welcome to"
        break;
    
    case 1:
        welcomeMsg = "Here is "
        break;

    case 2:
        welcomeMsg = "Chose "
        break;

    case 3:
        welcomeMsg = "Let's use "
        break;

    case 4:
        welcomeMsg = "Come on, "
        break;

    case 5:
        welcomeMsg = "New trip? "
        break;

    case 6:
        welcomeMsg = "Our solution, "
        break;

    default:
        break;
}
</script>

<template>
    <div class="home">
        <h1>{{ welcomeMsg }} Nex<span class="blue-text">Trip</span>!</h1>

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

    .home-line {
        margin-right: auto;
        margin-left: auto;
        width: fit-content;
    }
}
</style>
