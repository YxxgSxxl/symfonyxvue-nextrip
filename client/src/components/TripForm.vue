<script setup lang="ts">
let city1: string = ""
let city2: string = ""

function submitForm() {
    // alert(this.city1 + this.city2)
    emit('search', [city1, city2]);
}
</script>

<template>
    <form class="tripform">
        <div class="tripform-container">
            <input type="text" name="" id="" v-model.trim="city1" placeholder="First city">
            <p>And</p>
            <input type="text" name="" id="" v-model.trim="city2" placeholder="Second city">
            <input type="submit" value="Show Results" @click="submitForm()" @keydown.enter="submitForm()">
        </div>
    </form>
</template>

<style lang="scss" scoped>
.tripform {

    &-container {
        background-color: rgba($color: #000000, $alpha: .1);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        width: 80%;
        padding: 2rem 1.5rem 2rem 1.5rem;
        border-radius: 15px;
        align-items: center;
        margin-left: auto;
        margin-right: auto;
    }
}
</style>