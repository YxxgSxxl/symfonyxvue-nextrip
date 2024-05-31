<script setup lang="ts">
let city1: string = ""
let city2: string = ""

function submitForm() {
    alert("g,fhnfnfb")
}
</script>

<template>
    <form class="tripform">
        <div class="tripform-container">
            <input type="text" name="" id="" v-model="city1">
            <p>Or</p>
            <input type="text" name="" id="" v-model="city2">
            <input type="submit" value="Show Results" @submit="submitForm()">
        </div>
    </form>
</template>

<style lang="scss" scoped>
.tripform {

    &-container {
        background-color: rgba($color: #000000, $alpha: .1);
        display: flex;
        flex-direction: column;
        width: 80%;
        padding: 2rem 1.5rem 2rem 1.5rem;
        border-radius: 15px;
        align-items: center;
        margin-left: auto;
        margin-right: auto;
    }
}
</style>