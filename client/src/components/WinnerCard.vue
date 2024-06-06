<script setup lang="ts">
const props = defineProps({
    winnerData: Object,
});
</script>

<template>
    {{ props.winnerData?.city1 }}

    <div class="winnerCard">
        <div class="winnercard-name">
            <img src="../../public/crown.svg" alt="Winner Crown" width="50">
            <h2>{{ props.winnerData?.city1.name }}</h2>
        </div>
        azfza
    </div>
</template>

<style lang="scss" scoped>
.winnercard {
    display: flex;
    flex-direction: column;
    width: 80%;

    &-name {
        text-align: center;
    }
}
</style>