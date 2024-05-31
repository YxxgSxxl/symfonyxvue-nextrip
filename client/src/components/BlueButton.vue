<script setup lang="ts">
const props = defineProps({
  name: String,
  icon: String
});
</script>

<template>
    <router-link to="/chooseNextTrip"><button class="blue--btn">{{ props.name }}</button></router-link>
</template>

<style lang="scss" scoped>
    .blue--btn {
        padding: 8px 20px;
        border: 0;
        border-radius: 20px;
        background-color: #2ba8fb;
        color: #ffffff;
        font-weight: Bold;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        cursor: pointer;
    }

    .blue--btn:hover {
        background-color: #6fc5ff;
        box-shadow: 0 0 20px #6fc5ff50;
        transform: scale(1.1);
    }

    .blue--btn:active {
        background-color: #3d94cf;
        transition: all 0.25s;
        -webkit-transition: all 0.25s;
        box-shadow: none;
        transform: scale(0.98);
    }
</style>