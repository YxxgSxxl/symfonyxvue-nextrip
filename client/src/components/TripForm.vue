<script setup lang="ts">
import { defineEmits, ref } from 'vue';

let emit = defineEmits(['search'])

let city1: any = ref("")
let city2: any = ref("")

let result: any = ref([])

function submitForm() {
    // alert(this.city1 + this.city2)
    result.value.push(city1, city2)
    
    emit('search', result)
}
</script>

<template>
    <form class="tripform" method="post">
        <div class="tripform-container">
            <input type="text" name="" id="" v-model.trim="city1" placeholder="First city">
            <p>And</p>
            <input type="text" name="" id="" v-model.trim="city2" placeholder="Second city">
            <input type="submit" value="Compare" @click="submitForm()" @keydown.enter="submitForm()">
            <!-- {{ result }} -->
            <small>You need more informations ?<br><router-link to="/about">click here</router-link></small>
        </div>
    </form>
</template>

<style lang="scss" scoped>
.tripform {
    &-container {
        background-color: rgba($color: #000000, $alpha: .03);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        width: 80%;
        padding: 2rem 1.5rem 2rem 1.5rem;
        margin: 0 auto 4rem auto;
        border-radius: 15px;
        align-items: center;

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

    /* Tablets Responsive */
    @media (min-width: 768px) {
        .tripform {
            &-container {
                width: 50%;
            }
        }
    }

    /* Large desktop Responsive */
    @media (min-width: 1200px) {
        .tripform {
            &-container {
                width: 20%;
            }
        }
    }
}
</style>