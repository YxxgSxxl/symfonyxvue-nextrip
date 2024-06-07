<script setup lang="ts">
const props = defineProps({
    winnerData: Object,
    weatherData: Object
});
</script>

<template>
    <!-- {{ props.winnerData?.city1 }} -->

    <div class="winnercard">
        <div class="winnercard-name">
            <img src="/crown.svg" alt="Winner Crown" width="50">
            <h2>✈️ {{ props.winnerData?.city1.name }}</h2>
        </div>
        <p>Is the best city to have a trip for next week!</p><br><br>
        <div class="winnercard-average">
            <p>Average informations (5 days)</p><br>
            <div class="winnercard-infos">
                <h3 style="color: green;">{{ winnerData?.city1.name }}</h3>
                <div class="winnercard-temp">
                    <span class="winnercard-icons">🌡</span> {{ winnerData?.city1.temp }}°C // Attention à fix, toutes ces valeurs ne sont pas la moyenne du temps sur 5j mais l'écart par rapport aux valeurs définies qui donnent les points tout ça en moyenne
                </div>
            
                <div class="winnercard-humidity">
                    <span class="winnercard-icons">💧</span> {{ winnerData?.city1.humidity }}%
                </div>
            
                <div class="winnercard-clouds">
                    <span class="winnercard-icons">☁️</span> {{ winnerData?.city1.clouds }}%
                </div><br>

                <div class="winnercard-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v13M5 12l7 7 7-7"/></svg>
                </div><br>

                <h3 style="color: red;">{{ winnerData?.city2.name }}</h3>
                <div class="winnercard-temp">
                    <span class="winnercard-icons">🌡</span> {{ winnerData?.city2.temp }}°C
                </div>
            
                <div class="winnercard-humidity">
                    <span class="winnercard-icons">💧</span> {{ winnerData?.city2.humidity }}%
                </div>
            
                <div class="winnercard-clouds">
                    <span class="winnercard-icons">☁️</span> {{ winnerData?.city2.clouds }}%
                </div>
                <!-- {{ winnerData?.city2 }} -->
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.winnercard {
    background: rgb(255, 255, 255);
    border-radius: 16px;
    box-shadow: 0 2px 30px rgba(52, 52, 52, 0.1);
    backdrop-filter: blur(5.8px);
    -webkit-backdrop-filter: blur(5.8px);
    border: 1px solid rgba(207, 207, 207, 0.66);
    padding: 1.2rem 1rem 1.5rem 1rem;
    align-items: center;
    position: relative;
    width: 80%;
    margin-right: auto;
    margin-left: auto;
    display: flex;
    flex-direction: column;
    margin-bottom: 2rem;

    h2 {
        background: linear-gradient(#00aaff, #007ab7);
        background-size: 100%;
        background-clip: text;
        -webkit-background-clip: text;
        -moz-background-clip: text;
        -webkit-text-fill-color: transparent; 
        -moz-text-fill-color: transparent;

        // filter: drop-shadow(6px 3px 2px rgba(0, 0,0, .5));
    }

    &-name {
        text-align: center;
    }
}

@media (min-width: 768px) {
    .winnercard {
            width: 50%;
    }
}

/* Large desktop Responsive */
@media (min-width: 1200px) {
    .winnercard {
            width: 25%;
    }
}
</style>