<script setup lang="ts">
</script>

<template>
    <div class="about">
        <h1>About <span class="blue-text">us</span></h1>
        <div class="about-texts">
            <p>On NexTrip, you can compare between multiple cities wich one is the best one to go.</p>
            <p>We are making the choice for you! By checking wich city you chosed has the best weather (on 5 consecutive days)</p>
            <p>We take our weather information with <a href="https://openweathermap.org/api" target="_blank">OpenWeatherMap API</a></p>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.about {
    padding: 0rem 2rem 0rem 2rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    
    h1 {
        margin-top: 0;
    }

    a {
        color: #3e96bf;
        text-decoration: underline;
    }

    &-texts {
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        
        max-width: 400px;
    }
}

@media only screen and (max-width: 600px) {
    a:hover {
        text-decoration: none;
    }
}


</style>