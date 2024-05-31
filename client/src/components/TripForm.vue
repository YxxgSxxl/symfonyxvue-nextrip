<script setup lang="ts">
import { defineEmits, ref } from 'vue';

let emit = defineEmits(['search'])

let city1: string = ref("")
let city2: string = ref("")

let result: Array = ref([])


function submitForm() {
    // alert(this.city1 + this.city2)
    
    emit('search', city1);

    result.value.push(this.city1, this.city2)
}
</script>

<template>
    <form class="tripform">
        <div class="tripform-container">
            <input type="text" name="" id="" v-model.trim="city1" placeholder="First city">
            <p>And</p>
            <input type="text" name="" id="" v-model.trim="city2" placeholder="Second city">
            <input type="submit" value="Show Results" @click="submitForm()" @keydown.enter="submitForm()">
            {{ result }}
        </div>
    </form>
</template>

<style lang="scss" scoped>
.tripform {
    &-container {
        background-color: rgba($color: #000000, $alpha: .05);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        width: 80%;
        padding: 2rem 1.5rem 2rem 1.5rem;
        border-radius: 15px;
        align-items: center;
        margin-left: auto;
        margin-right: auto;

        input[type=text] {
	        appearance: none;
	        border: none;
	        outline: none;
	        border-bottom: .2em solid #006fb9;
	        background: rgba(#006fb9, .1);
	        border-radius: .2em .2em 0 0;
	        padding: .5em;
            width: 200px;
	        color: #000000;
        }

        input[type=submit] {
            background-color: #006fb9;
            color: white;
            border: none;
            border-radius: 15px;
            padding: .5rem 2rem .5rem 2rem;
        }

        input[type=submit]:hover {
            cursor: pointer;
        }
    }
}
</style>