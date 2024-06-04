<script setup lang="ts">
const props = defineProps({
  weatherData: Object,
});

function imgSource(source): string {
    return `https://openweathermap.org/img/w/${source}.png`
}
</script>

<template>
    <!-- {{ weatherData.data }} -->

    <div class="tripcard">
        <p>TODAY</p>
        <div class="tripcard-icon">
            <img :src="imgSource(weatherData?.weather[0].icon)" alt=""> 
        </div>

        <div class="tripcard-name">
            {{ weatherData?.name }}, {{ weatherData?.sys.country }}
        </div>

        <div class="tripcard-infos">
            <div class="tripcard-temp">
                <span class="tripcard-icons">🌡</span> {{ weatherData?.main.temp }}°C
            </div>
    
            <div class="tripcard-humidity">
                <span class="tripcard-icons">💧</span> {{ weatherData?.main.humidity }}%
            </div>
    
            <div class="tripcard-clouds">
                <span class="tripcard-icons">☁️</span> {{ weatherData?.clouds.all }}%
            </div>
    
            <div class="tripcard-wind">
                <span class="tripcard-icons">💨</span> {{ weatherData?.wind.speed }} Km/h
            </div>
        </div>

        <!-- {{ weatherData }} -->
    </div>
</template>

<style lang="scss" scoped>
.tripcard {
    background-color: rgba(62, 150, 191, .2);
    padding: 1.2rem 1rem 1.5rem 1rem;
    border-radius: 10px;
    align-items: center;
    position: relative;

    & p {
        text-align: center;
        font-weight: 600;
        color: rgba(0, 0, 0, .4);
        margin-bottom: 1rem;
    }

    &-icon {
        position: absolute;
        top: 5px;
        left: 5px;
    }

    &-icons {
        font-size: x-large;
    }

    &-name {
        align-items: center;
        text-align: center;
        font-size: 1.5em;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    &-infos {
        font-size: larger;
        align-items: start;
        margin-right: auto;
        margin-left: auto;
        width: fit-content;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }
}
</style>